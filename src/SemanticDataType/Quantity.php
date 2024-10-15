<?php

declare(strict_types=1);

namespace Tiime\EN16931\SemanticDataType;

final readonly class Quantity extends DecimalNumber
{
    public const int DECIMALS = 4;

    public function __construct(float $value)
    {
        parent::__construct($value, self::DECIMALS);
    }
}
