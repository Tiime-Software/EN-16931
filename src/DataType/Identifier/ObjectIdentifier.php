<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\Codelist\ReferenceQualifierCodeUNTDID1153;

class ObjectIdentifier
{
    public function __construct(public readonly string $value, public readonly ?ReferenceQualifierCodeUNTDID1153 $scheme = null)
    {
    }
}
