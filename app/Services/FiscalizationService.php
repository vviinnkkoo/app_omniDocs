<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Str;

class FiscalizationService
{
    protected string $endpoint;
    protected string $certPath;
    protected string $certPass;

    public function __construct()
    {
        // FINA endpoint (provjera test/prod)
        //$this->endpoint = 'https://cis.porezna-uprava.hr:8449/FiskalizacijaService';
        $this->endpoint = 'https://cistest.apis.porezna-uprava.hr:8449/FiskalizacijaService';

        $this->certPath = storage_path('certs/fina.p12');
        $this->certPass = env('FISCAL_CERT_PASS');
    }

    /**
     * Fiscalize an invoice
     */
    public function fiscalize(int $invoiceId): array
    {
        $invoice = Invoice::findOrFail($invoiceId);

        // 1. Generate ZKI
        $zki = $this->generateZKI($invoice);

        // 2. Generate minimal XML
        $xml = $this->generateXML($invoice, $zki);

        // 3. Send SOAP request
        $response = $this->sendSOAP($xml);

        // 4. Save JIR and ZKI to invoice
        if (isset($response->JIR)) {
            $invoice->jir = $response->JIR;
            $invoice->zki = $zki;
            $invoice->save();
        }

        return [
            'zki' => $zki,
            'jir' => $response->JIR ?? null,
        ];
    }

    /**
     * Generate ZKI hash
     */
    protected function generateZKI(Invoice $invoice): string
    {
        $data = $invoice->id .
                $invoice->oib .
                $invoice->issue_date->format('Y-m-d\TH:i:s') .
                $invoice->total;

        $cert = file_get_contents($this->certPath);
        openssl_pkcs12_read($cert, $certs, $this->certPass);
        $privateKey = $certs['pkey'];

        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return strtoupper(bin2hex($signature));
    }

    /**
     * Generate minimal XML for FINA
     */
    protected function generateXML(Invoice $invoice, string $zki): string
    {
        $xml = new \SimpleXMLElement('<Invoice></Invoice>');
        $xml->addChild('OIB', $invoice->oib);
        $xml->addChild('MessageID', Str::uuid()->toString());
        $xml->addChild('DateTime', $invoice->issue_date->format('c'));
        $xml->addChild('ZKI', $zki);
        $xml->addChild('Total', $invoice->total);

        return $xml->asXML();
    }

    /**
     * Send SOAP request
     */
    protected function sendSOAP(string $xml): object
    {
        $client = new \SoapClient($this->endpoint, [
            'local_cert' => $this->certPath,
            'passphrase' => $this->certPass,
            'trace' => 1,
        ]);

        $params = ['InvoiceRequest' => $xml];
        return $client->__soapCall('SubmitInvoice', [$params]);
    }
}
