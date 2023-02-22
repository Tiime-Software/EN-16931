<?php

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\DataType\ObjectSchemeCode;

class ObjectIdentifier
{
    public function __construct(public readonly string $value, public readonly ?ObjectSchemeCode $scheme)
    {
    }
}
