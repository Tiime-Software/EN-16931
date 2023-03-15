<?php

namespace Tiime\EN16931\SemanticDataType;

class Percentage extends SemanticDataType
{
    public const DECIMALS = 2;

    public function __construct(float $value)
    {
        parent::__construct($value, self::DECIMALS);
    }
}