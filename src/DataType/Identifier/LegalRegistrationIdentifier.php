<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\Codelist\InternationalCodeDesignator;

final readonly class LegalRegistrationIdentifier
{
    public function __construct(
        public string $value,
        public ?InternationalCodeDesignator $scheme = null
    ) {
    }
}
