<?php

namespace App\Services\Fiscalization\Generators\Xmldsig;

/**
 * Class representing SignedInfoType
 *
 *
 * XSD Type: SignedInfoType
 */
class SignedInfoType
{
    /**
     * @var string $id
     */
    private $id = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\CanonicalizationMethod $canonicalizationMethod
     */
    private $canonicalizationMethod = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\SignatureMethod $signatureMethod
     */
    private $signatureMethod = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\Reference[] $reference
     */
    private $reference = [
        
    ];

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
     * Gets as canonicalizationMethod
     *
     * @return \App\Services\Fiscalization\Generators\Xmldsig\CanonicalizationMethod
     */
    public function getCanonicalizationMethod()
    {
        return $this->canonicalizationMethod;
    }

    /**
     * Sets a new canonicalizationMethod
     *
     * @param \App\Services\Fiscalization\Generators\Xmldsig\CanonicalizationMethod $canonicalizationMethod
     * @return self
     */
    public function setCanonicalizationMethod(\App\Services\Fiscalization\Generators\Xmldsig\CanonicalizationMethod $canonicalizationMethod)
    {
        $this->canonicalizationMethod = $canonicalizationMethod;
        return $this;
    }

    /**
     * Gets as signatureMethod
     *
     * @return \App\Services\Fiscalization\Generators\Xmldsig\SignatureMethod
     */
    public function getSignatureMethod()
    {
        return $this->signatureMethod;
    }

    /**
     * Sets a new signatureMethod
     *
     * @param \App\Services\Fiscalization\Generators\Xmldsig\SignatureMethod $signatureMethod
     * @return self
     */
    public function setSignatureMethod(\App\Services\Fiscalization\Generators\Xmldsig\SignatureMethod $signatureMethod)
    {
        $this->signatureMethod = $signatureMethod;
        return $this;
    }

    /**
     * Adds as reference
     *
     * @return self
     * @param \App\Services\Fiscalization\Generators\Xmldsig\Reference $reference
     */
    public function addToReference(\App\Services\Fiscalization\Generators\Xmldsig\Reference $reference)
    {
        $this->reference[] = $reference;
        return $this;
    }

    /**
     * isset reference
     *
     * @param int|string $index
     * @return bool
     */
    public function issetReference($index)
    {
        return isset($this->reference[$index]);
    }

    /**
     * unset reference
     *
     * @param int|string $index
     * @return void
     */
    public function unsetReference($index)
    {
        unset($this->reference[$index]);
    }

    /**
     * Gets as reference
     *
     * @return \App\Services\Fiscalization\Generators\Xmldsig\Reference[]
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Sets a new reference
     *
     * @param \App\Services\Fiscalization\Generators\Xmldsig\Reference[] $reference
     * @return self
     */
    public function setReference(array $reference)
    {
        $this->reference = $reference;
        return $this;
    }
}

