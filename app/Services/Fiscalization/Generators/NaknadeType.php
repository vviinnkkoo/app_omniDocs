<?php

namespace App\Services\Fiscalization\Generators;

/**
 * Class representing NaknadeType
 *
 *
 * XSD Type: NaknadeType
 */
class NaknadeType
{
    /**
     * @var \App\Services\Fiscalization\Generators\NaknadaType[] $naknada
     */
    private $naknada = [
        
    ];

    /**
     * Adds as naknada
     *
     * @return self
     * @param \App\Services\Fiscalization\Generators\NaknadaType $naknada
     */
    public function addToNaknada(\App\Services\Fiscalization\Generators\NaknadaType $naknada)
    {
        $this->naknada[] = $naknada;
        return $this;
    }

    /**
     * isset naknada
     *
     * @param int|string $index
     * @return bool
     */
    public function issetNaknada($index)
    {
        return isset($this->naknada[$index]);
    }

    /**
     * unset naknada
     *
     * @param int|string $index
     * @return void
     */
    public function unsetNaknada($index)
    {
        unset($this->naknada[$index]);
    }

    /**
     * Gets as naknada
     *
     * @return \App\Services\Fiscalization\Generators\NaknadaType[]
     */
    public function getNaknada()
    {
        return $this->naknada;
    }

    /**
     * Sets a new naknada
     *
     * @param \App\Services\Fiscalization\Generators\NaknadaType[] $naknada
     * @return self
     */
    public function setNaknada(array $naknada)
    {
        $this->naknada = $naknada;
        return $this;
    }
}

