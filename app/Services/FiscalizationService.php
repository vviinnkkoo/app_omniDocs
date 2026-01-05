<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Settings;

class FiscalizationService
{
    protected string $endpoint;
    protected string $certPath;
    protected string $certPass;
    protected bool $isProduction;

    public function __construct(bool $isProduction = false)
    {
        $this->isProduction = $isProduction;
        
        if ($isProduction) {
            $this->endpoint = 'https://cis.porezna-uprava.hr:8449/FiskalizacijaService';
            $this->certPath = storage_path('app/certs/production.p12');
        } else {
            $this->endpoint = 'https://cistest.apis-it.hr:8449/FiskalizacijaServiceTest';
            $this->certPath = storage_path('app/domains/localhost/cert/69219061360_NEW.p12');
        }
        
        $this->certPass = 'Xsy6rXFdbusffye';
    }

    public function fiscalize(int $invoiceId): array
    {
        $invoice = Invoice::with(['businessSpace', 'businessDevice', 'invoiceItemList'])->findOrFail($invoiceId);
        $companyOib = Settings::where('setting_name', 'company_oib')->value('setting_value');
        $total = $invoice->invoiceItemList->sum('total');

        $zki = $this->generateZKI($invoice, $companyOib, $total);
        $xml = $this->generateXML($invoice, $zki, $companyOib, $total);
        $signedXml = $this->signXML($xml);

        try {
            $response = $this->sendSOAP($signedXml);
            $jir = $this->extractJIR($response);

            $invoice->jir = $jir;
            $invoice->zki = $zki;
            $invoice->save();

            return [
                'success' => true,
                'zki' => $zki,
                'jir' => $jir,
                'raw_response' => $response,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'zki' => $zki,
                'jir' => null,
            ];
        }
    }

    protected function generateZKI(Invoice $invoice, string $companyOib, float $total): string
    {
        $oznPosPr = $invoice->businessSpace?->name ?: '1';
        $oznNapUr = $invoice->businessDevice?->name ?: '1';
        
        $data =
            $companyOib .
            $invoice->issued_at->format('d.m.Y H:i:s') .
            $invoice->number .
            $oznPosPr .
            $oznNapUr .
            number_format($total, 2, '.', '');

        $certContent = file_get_contents($this->certPath);
        
        if (!openssl_pkcs12_read($certContent, $certs, $this->certPass)) {
            throw new \Exception("Cannot read PKCS#12 certificate");
        }

        if (!openssl_sign($data, $signature, $certs['pkey'], OPENSSL_ALGO_SHA1)) {
            throw new \Exception("Cannot sign data");
        }

        return md5($signature);
    }

    protected function generateXML(Invoice $invoice, string $zki, string $companyOib, float $total): string
    {
        if (!$invoice->businessSpace || !$invoice->businessSpace->name) {
            throw new \Exception('Poslovni prostor nije definiran za račun #' . $invoice->id);
        }
        
        if (!$invoice->businessDevice || !$invoice->businessDevice->name) {
            throw new \Exception('Naplatni uređaj nije definiran za račun #' . $invoice->id);
        }
        
        $oznPosPr = $invoice->businessSpace->name;
        $oznNapUr = $invoice->businessDevice->name;
        
        $billNumber = new \App\Services\Fiscalization\Generators\BrojRacunaType(
            $invoice->number,
            $oznPosPr,
            $oznNapUr
        );

        $bill = new \App\Services\Fiscalization\Generators\RacunType();
        $bill->setOib($companyOib);
        $bill->setOznSlijed("P");
        $bill->setUSustPdv(false);
        $bill->setDatVrijeme($invoice->issued_at->format('d.m.Y\TH:i:s'));
        $bill->setBrRac($billNumber);
        $bill->setIznosUkupno($total);
        $bill->setNacinPlac("G");
        $bill->setOibOper($companyOib);
        $bill->setZastKod($zki);
        $bill->setNakDost(false);

        $billRequest = new \App\Services\Fiscalization\Generators\RacunZahtjev();
        $billRequest->setRacun($bill);

        $zaglavlje = new \App\Services\Fiscalization\Generators\ZaglavljeType();
        $billRequest->setZaglavlje($zaglavlje);

        $serializer = new \App\Services\Fiscalization\XMLSerializer($billRequest);
        return $serializer->toXml();
    }

    protected function signXML(string $xml): string
    {
        $certContent = file_get_contents($this->certPath);
        
        if (!openssl_pkcs12_read($certContent, $certs, $this->certPass)) {
            throw new \Exception("Cannot read certificate for signing");
        }

        $certData = openssl_x509_parse($certs['cert']);
        $privateKey = openssl_pkey_get_private($certs['pkey'], $this->certPass);

        $doc = new \DOMDocument();
        $doc->loadXML($xml);

        $canonical = $doc->C14N();
        $digestValue = base64_encode(hash('sha1', $canonical, true));

        $rootElem = $doc->documentElement;

        $signatureNode = $rootElem->appendChild(new \DOMElement('Signature'));
        $signatureNode->setAttribute('xmlns', 'http://www.w3.org/2000/09/xmldsig#');

        $signedInfoNode = $signatureNode->appendChild(new \DOMElement('SignedInfo'));
        $signedInfoNode->setAttribute('xmlns', 'http://www.w3.org/2000/09/xmldsig#');

        $canonMethod = $signedInfoNode->appendChild(new \DOMElement('CanonicalizationMethod'));
        $canonMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');

        $sigMethod = $signedInfoNode->appendChild(new \DOMElement('SignatureMethod'));
        $sigMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');

        $reference = $signedInfoNode->appendChild(new \DOMElement('Reference'));
        $reference->setAttribute('URI', '#' . $doc->documentElement->getAttribute('Id'));

        $transforms = $reference->appendChild(new \DOMElement('Transforms'));
        
        $transform1 = $transforms->appendChild(new \DOMElement('Transform'));
        $transform1->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');
        
        $transform2 = $transforms->appendChild(new \DOMElement('Transform'));
        $transform2->setAttribute('Algorithm', 'http://www.w3.org/2001/10/xml-exc-c14n#');

        $digestMethod = $reference->appendChild(new \DOMElement('DigestMethod'));
        $digestMethod->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#sha1');

        $reference->appendChild(new \DOMElement('DigestValue', $digestValue));

        $signedInfoNode = $doc->getElementsByTagName('SignedInfo')->item(0);

        $signedInfoSignature = null;
        if (!openssl_sign($signedInfoNode->C14N(true), $signedInfoSignature, $privateKey, OPENSSL_ALGO_SHA1)) {
            throw new \Exception('Unable to sign the request');
        }

        $signatureNode = $doc->getElementsByTagName('Signature')->item(0);
        $signatureValueNode = new \DOMElement('SignatureValue', base64_encode($signedInfoSignature));
        $signatureNode->appendChild($signatureValueNode);

        $keyInfoNode = $signatureNode->appendChild(new \DOMElement('KeyInfo'));
        $x509DataNode = $keyInfoNode->appendChild(new \DOMElement('X509Data'));

        $certPureString = str_replace(['-----BEGIN CERTIFICATE-----', '-----END CERTIFICATE-----', "\n", "\r"], '', $certs['cert']);
        $x509CertNode = new \DOMElement('X509Certificate', trim($certPureString));
        $x509DataNode->appendChild($x509CertNode);

        $x509IssuerSerialNode = $x509DataNode->appendChild(new \DOMElement('X509IssuerSerial'));
        
        $issuer = $certData['issuer'];
        $x509IssuerName = sprintf('CN=%s, O=%s, C=%s', $issuer['CN'], $issuer['O'], $issuer['C']);
        $x509SerialNumber = base_convert($certData['serialNumber'], 16, 10);
        
        $x509IssuerNameNode = new \DOMElement('X509IssuerName', $x509IssuerName);
        $x509IssuerSerialNode->appendChild($x509IssuerNameNode);
        
        $x509SerialNumberNode = new \DOMElement('X509SerialNumber', $x509SerialNumber);
        $x509IssuerSerialNode->appendChild($x509SerialNumberNode);

        $envelope = new \DOMDocument();
        $envelope->loadXML('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
            <soapenv:Body></soapenv:Body>
        </soapenv:Envelope>');

        $envelope->encoding = 'UTF-8';
        $envelope->version = '1.0';
        
        $requestType = $doc->documentElement->localName;
        $requestTypeNode = $doc->getElementsByTagName($requestType)->item(0);
        $requestTypeNode = $envelope->importNode($requestTypeNode, true);

        $envelope->getElementsByTagName('Body')->item(0)->appendChild($requestTypeNode);
        
        return $envelope->saveXML();
    }

    protected function sendSOAP(string $xml): string
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->endpoint,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $xml,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLVERSION     => 6,
        ]);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception('CURL Error: ' . $error);
        }
        
        curl_close($ch);
        
        return $response;
    }

    protected function extractJIR(string $xmlResponse): string
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xmlResponse);
        
        $xpath = new \DOMXPath($doc);
        $xpath->registerNamespace('tns', 'http://www.apis-it.hr/fin/2012/types/f73');
        
        $jirNode = $xpath->query('//tns:Jir')->item(0);
        
        if (!$jirNode) {
            $errorNode = $xpath->query('//tns:SifraGreske')->item(0);
            if ($errorNode) {
                $errorMsg = $xpath->query('//tns:PorukaGreske')->item(0)->nodeValue ?? 'Unknown error';
                throw new \Exception($errorNode->nodeValue . ': ' . $errorMsg);
            }
            throw new \Exception('No JIR in response');
        }
        
        return $jirNode->nodeValue;
    }
}