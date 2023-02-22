<?php

namespace Tiime\EN16931\DataType\Identifier;

class VatIdentifier
{
    public function __construct(public readonly string $value)
    {
        // @todo : $value should be typed VatIdentificationNumber
    }
}
