<?php

namespace Tiime\EN16931\SemanticDataType;

class IntNumber extends SemanticDataType
{
    public const DECIMALS = 0;

    public function __construct(float $value)
    {
        parent::__construct($value, self::DECIMALS);
    }
}