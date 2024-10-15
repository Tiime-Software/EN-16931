<?php

declare(strict_types=1);

namespace Tiime\EN16931\SemanticDataType;

final readonly class Percentage extends DecimalNumber
{
    public const int DECIMALS = 2;

    public function __construct(float $value)
    {
        parent::__construct($value, self::DECIMALS);
    }
}
