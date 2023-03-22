<?php

declare(strict_types=1);

namespace Tiime\EN16931\SemanticDataType;

class Amount extends DecimalNumber
{
    public const DECIMALS = 2;

    public function __construct(float $value)
    {
        parent::__construct($value, self::DECIMALS);
    }
}
