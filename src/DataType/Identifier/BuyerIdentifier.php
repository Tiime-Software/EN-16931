<?php

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\DataType\InternationalCodeDesignator;

class BuyerIdentifier
{
    public function __construct(public readonly string $value, public readonly ?InternationalCodeDesignator $scheme)
    {
    }
}
