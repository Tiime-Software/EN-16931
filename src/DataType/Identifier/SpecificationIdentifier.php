<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

class SpecificationIdentifier
{
    public const MINIMUM = 'urn:factur-x.eu:1p0:minimum';
    public const BASIC_WL = 'urn:factur-x.eu:1p0:basicwl';
    public const BASIC = 'urn:cen.eu:en16931:2017#compliant#urn:factur-x.eu:1p0:basic';
    public const EN16931 = 'urn:cen.eu:en16931:2017';
    public const EXTENDED = 'urn:cen.eu:en16931:2017#conformant#urn:factur-x.eu:1p0:extended';

    public function __construct(public readonly string $value)
    {
    }
}
