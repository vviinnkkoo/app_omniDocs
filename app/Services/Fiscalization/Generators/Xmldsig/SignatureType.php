<?php

namespace App\Services\Fiscalization\Generators\Xmldsig;

/**
 * Class representing SignatureType
 *
 *
 * XSD Type: SignatureType
 */
class SignatureType
{
    /**
     * @var string $id
     */
    private $id = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\SignedInfo $signedInfo
     */
    private $signedInfo = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\SignatureValue $signatureValue
     */
    private $signatureValue = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\KeyInfo $keyInfo
     */
    private $keyInfo = null;

    /**
     * @var \App\Services\Fiscalization\Generators\Xmldsig\ObjectXsd[] $object
     */
    private $object = [
        
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
     * Gets as signedInfo
     *
     * @return \App\Services\Fiscalization\Generators\Xmldsig\SignedInfo
     */
    public function getSignedInfo()
    {
        return $this->signedInfo;
    }

    /**
     * Sets a new signedInfo
     *
     * @param \App\Services\Fiscalization\Generators\Xmldsig\SignedInfo $signedInfo
     * @return self
     */
    public function setSignedInfo(\App\Services\Fiscalization\Generators\Xmldsig\SignedInfo $signedInfo)
    {
        $this->signedInfo = $signedInfo;
        return $this;
    }

    /**
     * Gets as signatureValue
     *
     * @return \App\Services\Fiscalization\Generators\Xmldsig\SignatureValue
     */
    public function getSignatureValue()
    {
        return $this->signatureValue;
    }

    /**
     * Sets a new signatureValue
     *
     * @param \App\Services\Fiscalization\Generators\Xmldsig\SignatureValue $signatureValue
     * @return self
     */
    public function setSignatureValue(\App\Services\Fiscalization\Generators\Xmldsig\SignatureValue $signatureValue)
    {
        $this->signatureValue = $signatureValue;
        return $this;
    }

    /**
     * Gets as keyInfo
     *
     * @return \App\Services\Fiscalization\Generators\Xmldsig\KeyInfo
     */
    public function getKeyInfo()
    {
        return $this->keyInfo;
    }

    /**
     * Sets a new keyInfo
     *
     * @param \App\Services\Fiscalization\Generators\Xmldsig\KeyInfo $keyInfo
     * @return self
     */
    public function setKeyInfo(?\App\Services\Fiscalization\Generators\Xmldsig\KeyInfo $keyInfo = null)
    {
        $this->keyInfo = $keyInfo;
        return $this;
    }

    /**
     * Adds as object
     *
     * @return self
     * @param \App\Services\Fiscalization\Generators\Xmldsig\ObjectXsd $object
     */
    public function addToObject(\App\Services\Fiscalization\Generators\Xmldsig\ObjectXsd $object)
    {
        $this->object[] = $object;
        return $this;
    }

    /**
     * isset object
     *
     * @param int|string $index
     * @return bool
     */
    public function issetObject($index)
    {
        return isset($this->object[$index]);
    }

    /**
     * unset object
     *
     * @param int|string $index
     * @return void
     */
    public function unsetObject($index)
    {
        unset($this->object[$index]);
    }

    /**
     * Gets as object
     *
     * @return \App\Services\Fiscalization\Generators\Xmldsig\ObjectXsd[]
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Sets a new object
     *
     * @param \App\Services\Fiscalization\Generators\Xmldsig\ObjectXsd[] $object
     * @return self
     */
    public function setObject(array $object = null)
    {
        $this->object = $object;
        return $this;
    }
}

