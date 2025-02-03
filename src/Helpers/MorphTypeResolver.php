<?php

namespace Namu\WireChat\Helpers;

class MorphTypeResolver
{
    /**
     * Encodes the given raw type using hexadecimal encoding.
     *
     * @param  string  $rawType
     * @return string
     */
    public static function encode(string $rawType): string
    {
        return bin2hex($rawType);
    }

    /**
     * Decodes the given hex-encoded type back to its raw string.
     *
     * @param  string  $encodedType
     * @return string|false
     */
    public static function decode(string $encodedType)
    {
        return hex2bin($encodedType);
    }
}