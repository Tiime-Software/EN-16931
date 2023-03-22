<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\DataType\ElectronicAddressScheme;

class ElectronicAddressIdentifier
{
    public function __construct(public readonly string $value, public readonly ElectronicAddressScheme $scheme)
    {
    }
}
