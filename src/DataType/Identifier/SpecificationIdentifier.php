<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

readonly class SpecificationIdentifier
{
    public const string MINIMUM = 'urn:factur-x.eu:1p0:minimum';
    public const string BASICWL = 'urn:factur-x.eu:1p0:basicwl';
    public const string BASIC = 'urn:cen.eu:en16931:2017#compliant#urn:factur-x.eu:1p0:basic';
    public const string EN16931 = 'urn:cen.eu:en16931:2017';
    public const string EXTENDED = 'urn:cen.eu:en16931:2017#conformant#urn:factur-x.eu:1p0:extended';

    public const string DEMARRAGE = 'urn.cpro.gouv.fr:1p0:einvoicingextract#Base';
    public const string CIBLE = 'urn.cpro.gouv.fr:1p0:einvoicingextract#Full';

    public function __construct(
        public string $value
    ) {
    }
}
