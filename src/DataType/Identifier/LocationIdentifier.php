<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\Codelist\InternationalCodeDesignator;

readonly class LocationIdentifier
{
    public function __construct(
        public string $value,
        public ?InternationalCodeDesignator $scheme = null
    ) {
    }
}
