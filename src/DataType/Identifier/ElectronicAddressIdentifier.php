<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\Codelist\ElectronicAddressSchemeCode;

final readonly class ElectronicAddressIdentifier
{
    public function __construct(public string $value, public ElectronicAddressSchemeCode $scheme)
    {
    }
}
