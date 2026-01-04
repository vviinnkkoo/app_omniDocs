<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Settings;

use Illuminate\Support\Str;

class FiscalizationService
{
    protected string $endpoint;
    protected string $certPath;
    protected string $certPass;

    public function __construct()
    {
        // TEST endpoint
        $this->endpoint = 'https://cistest.apis.porezna-uprava.hr:8449/FiskalizacijaService';
        $this->certPath = storage_path('app/domains/localhost/cert/69219061360.F1.2.p12');
        $this->certPass = 'Xsy6rXFdbusffye';
    }

    public function fiscalize(int $invoiceId): array
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $companyOib = Settings::where('setting_name', 'company_oib')->value('setting_value');
        $total = $invoice->invoiceItemList->sum('total');

        $zki = $this->generateZKI($invoice, $companyOib, $total);
        $xml = $this->generateXML($invoice, $zki, $companyOib, $total);

        $response = $this->sendSOAP($xml);

        if (isset($response->Jir)) {
            $invoice->jir = (string) $response->Jir;
            $invoice->zki = $zki;
            $invoice->save();
        }

        return [
            'zki' => $zki,
            'jir' => $response->Jir ?? null,
            'raw_response' => $response,
        ];
    }

    protected function generateZKI(Invoice $invoice, string $companyOib, float $total): string
    {
        if (!file_exists($this->certPath)) {
            throw new \Exception("Certificate not found: {$this->certPath}");
        }

        dd($this->certPath, file_exists($this->certPath));

        $data =
            $companyOib .
            $invoice->issued_at->format('d.m.Y H:i:s') .
            $invoice->number .
            $invoice->businessSpace->label .
            $invoice->businessDevice->label .
            number_format($total, 2, '.', '');

        $certContent = file_get_contents($this->certPath);

            if (!openssl_pkcs12_read($certContent, $certs, $this->certPass)) {
                throw new \Exception("Cannot read PKCS#12 certificate");
            }

            openssl_sign($data, $signature, $certs['pkey'], OPENSSL_ALGO_SHA1);

            return strtoupper(bin2hex($signature));
        }

    protected function generateXML(Invoice $invoice, string $zki): string
    {
        $xml = new \SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>
            <tns:RacunZahtjev xmlns:tns="http://www.apis-it.hr/fin/2012/types/f73">
                <tns:Zaglavlje/>
                <tns:Racun/>
            </tns:RacunZahtjev>'
        );

        $xml->Zaglavlje->addChild('tns:IdPoruke', Str::uuid()->toString(), $xml->getNamespaces(true)['tns']);
        $xml->Zaglavlje->addChild('tns:DatumVrijeme', now()->format('d.m.Y\TH:i:s'), $xml->getNamespaces(true)['tns']);

        $racun = $xml->Racun;
        $racun->addChild('tns:Oib', $invoice->oib, $xml->getNamespaces(true)['tns']);
        $racun->addChild('tns:DatVrijeme', $invoice->issue_date->format('d.m.Y\TH:i:s'), $xml->getNamespaces(true)['tns']);

        $brRac = $racun->addChild('tns:BrRac', null, $xml->getNamespaces(true)['tns']);
        $brRac->addChild('tns:BrOznRac', $invoice->number, $xml->getNamespaces(true)['tns']);
        $brRac->addChild('tns:OznPosPr', $invoice->business_unit_code, $xml->getNamespaces(true)['tns']);
        $brRac->addChild('tns:OznNapUr', $invoice->cash_register_code, $xml->getNamespaces(true)['tns']);

        $racun->addChild('tns:IznosUkupno', number_format($invoice->total, 2, '.', ''), $xml->getNamespaces(true)['tns']);
        $racun->addChild('tns:NacinPlac', 'G', $xml->getNamespaces(true)['tns']);
        $racun->addChild('tns:OibOper', $invoice->oib, $xml->getNamespaces(true)['tns']);
        $racun->addChild('tns:ZastKod', $zki, $xml->getNamespaces(true)['tns']);
        $racun->addChild('tns:NakDost', 'false', $xml->getNamespaces(true)['tns']);

        return $xml->asXML();
    }

    protected function sendSOAP(string $xml): object
    {
        $client = new \SoapClient(null, [
            'location'      => $this->endpoint,
            'uri'           => 'http://www.apis-it.hr/fin/2012/types/f73',
            'local_cert'    => $this->certPath,
            'passphrase'    => $this->certPass,
            'trace'         => 1,
            'exceptions'    => true,
        ]);

        return $client->__soapCall('RacunZahtjev', [
            new \SoapVar($xml, XSD_ANYXML),
        ]);
    }
}