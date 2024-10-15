<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

final readonly class MandateReferenceIdentifier
{
    public function __construct(public string $value)
    {
    }
}
