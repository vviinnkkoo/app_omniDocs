<?php

namespace App\Services\Fiscalization\Generators\PromijeniNacPlacZahtjev;

/**
 * Class representing PromijeniNacPlacZahtjevAType
 */
class PromijeniNacPlacZahtjevAType
{
    /**
     * Atribut za potrebe digitalnog potpisa, u njega se stavlja referentni na koji se referencira digitalni potpis.
     *
     * @var string $id
     */
    private $id = null;

    /**
     * @var \App\Services\Fiscalization\Generators\ZaglavljeType $zaglavlje
     */
    private $zaglavlje = null;

    /**
     * @var \App\Services\Fiscalization\Generators\RacunPNPType $racun
     */
    private $racun = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\Signature $signature
     */
    private $signature = null;

    /**
     * Gets as id
     *
     * Atribut za potrebe digitalnog potpisa, u njega se stavlja referentni na koji se referencira digitalni potpis.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets a new id
     *
     * Atribut za potrebe digitalnog potpisa, u njega se stavlja referentni na koji se referencira digitalni potpis.
     *
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets as zaglavlje
     *
     * @return \App\Services\Fiscalization\Generators\ZaglavljeType
     */
    public function getZaglavlje()
    {
        return $this->zaglavlje;
    }

    /**
     * Sets a new zaglavlje
     *
     * @param \App\Services\Fiscalization\Generators\ZaglavljeType $zaglavlje
     * @return self
     */
    public function setZaglavlje(\App\Services\Fiscalization\Generators\ZaglavljeType $zaglavlje)
    {
        $this->zaglavlje = $zaglavlje;
        return $this;
    }

    /**
     * Gets as racun
     *
     * @return \App\Services\Fiscalization\Generators\RacunPNPType
     */
    public function getRacun()
    {
        return $this->racun;
    }

    /**
     * Sets a new racun
     *
     * @param \App\Services\Fiscalization\Generators\RacunPNPType $racun
     * @return self
     */
    public function setRacun(\App\Services\Fiscalization\Generators\RacunPNPType $racun)
    {
        $this->racun = $racun;
        return $this;
    }

    /**
     * Gets as signature
     *
     * @return \App\Services\Fiscalization\Generators\Xmldsig\Signature
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Sets a new signature
     *
     * @param \App\Services\Fiscalization\Generators\Xmldsig\Signature $signature
     * @return self
     */
    public function setSignature(?\App\Services\Fiscalization\Generators\Xmldsig\Signature $signature = null)
    {
        $this->signature = $signature;
        return $this;
    }
}

