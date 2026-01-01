<?php

namespace App\Enums;

abstract class BaseEnum
{
    protected const DEFINITIONS = [];

    public static function keys(): array
    {
        return array_keys(static::DEFINITIONS);
    }

    public static function options(): array
    {
        return static::DEFINITIONS;
    }

    public static function label(string $key): ?string
    {
        return static::DEFINITIONS[$key] ?? null;
    }
}
