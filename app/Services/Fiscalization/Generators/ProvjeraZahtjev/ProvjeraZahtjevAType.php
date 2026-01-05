<?php

namespace App\Services\Fiscalization\Generators\ProvjeraZahtjev;

/**
 * Class representing ProvjeraZahtjevAType
 */
class ProvjeraZahtjevAType
{
    /**
     * @var string $id
     */
    private $id = null;

    /**
     * @var \App\Services\Fiscalization\Generators\ZaglavljeType $zaglavlje
     */
    private $zaglavlje = null;

    /**
     * @var \App\Services\Fiscalization\Generators\RacunType $racun
     */
    private $racun = null;

    /**
     * @var \App\Services\Fiscalization\Generators\RacunPDType $racunPD
     */
    private $racunPD = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\Signature $signature
     */
    private $signature = null;

    /**
     * Gets as id
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
     * @return \App\Services\Fiscalization\Generators\RacunType
     */
    public function getRacun()
    {
        return $this->racun;
    }

    /**
     * Sets a new racun
     *
     * @param \App\Services\Fiscalization\Generators\RacunType $racun
     * @return self
     */
    public function setRacun(?\App\Services\Fiscalization\Generators\RacunType $racun = null)
    {
        $this->racun = $racun;
        return $this;
    }

    /**
     * Gets as racunPD
     *
     * @return \App\Services\Fiscalization\Generators\RacunPDType
     */
    public function getRacunPD()
    {
        return $this->racunPD;
    }

    /**
     * Sets a new racunPD
     *
     * @param \App\Services\Fiscalization\Generators\RacunPDType $racunPD
     * @return self
     */
    public function setRacunPD(?\App\Services\Fiscalization\Generators\RacunPDType $racunPD = null)
    {
        $this->racunPD = $racunPD;
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

