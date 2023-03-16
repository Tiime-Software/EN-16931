<?php

namespace Tiime\EN16931\SemanticDataType;

class Quantity extends DecimalNumber
{
    public const DECIMALS = 4;

    public function __construct(float $value)
    {
        parent::__construct($value, self::DECIMALS);
    }
}