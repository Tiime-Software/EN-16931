<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

class SpecificationIdentifier
{
    public const MINIMUM = 'urn:factur-x.eu:1p0:minimum';
    public const BASIC_WL = '';
    public const BASIC = 'urn:cen.eu:en16931#compliant#factur-x.eu:1p0:basic';
    public const EN16931 = '';
    public const EXTENDED = '';

    public function __construct(public readonly string $value)
    {
    }
}
