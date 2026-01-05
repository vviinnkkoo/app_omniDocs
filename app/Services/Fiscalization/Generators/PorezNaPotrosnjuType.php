<?php

namespace App\Services\Fiscalization\Generators;

/**
 * Class representing PorezNaPotrosnjuType
 *
 *
 * XSD Type: PorezNaPotrosnjuType
 */
class PorezNaPotrosnjuType
{
    /**
     * @var \App\Services\Fiscalization\Generators\PorezType[] $porez
     */
    private $porez = [
        
    ];

    /**
     * Adds as porez
     *
     * @return self
     * @param \App\Services\Fiscalization\Generators\PorezType $porez
     */
    public function addToPorez(\App\Services\Fiscalization\Generators\PorezType $porez)
    {
        $this->porez[] = $porez;
        return $this;
    }

    /**
     * isset porez
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPorez($index)
    {
        return isset($this->porez[$index]);
    }

    /**
     * unset porez
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPorez($index)
    {
        unset($this->porez[$index]);
    }

    /**
     * Gets as porez
     *
     * @return \App\Services\Fiscalization\Generators\PorezType[]
     */
    public function getPorez()
    {
        return $this->porez;
    }

    /**
     * Sets a new porez
     *
     * @param \App\Services\Fiscalization\Generators\PorezType[] $porez
     * @return self
     */
    public function setPorez(array $porez)
    {
        $this->porez = $porez;
        return $this;
    }
}

