<?php

namespace Tiime\EN16931\SemanticDataType;

class IntegerNumber implements Number
{
    public function __construct(private readonly int $value)
    {
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function add(Number $number, ?int $decimals = null): float
    {
        $result = (float) bcadd((string) $this->getValue(), (string) $number->getValue(), Number::BC_MATH_ROUNDING);

        if (null !== $decimals) {
            return round($result, $decimals);
        }

        return $result;
    }

    public function subtract(Number $number, ?int $decimals = null): float
    {
        $result = (float) bcsub((string) $this->getValue(), (string) $number->getValue(), Number::BC_MATH_ROUNDING);

        if (null !== $decimals) {
            return round($result, $decimals);
        }

        return $result;
    }

    public function multiply(Number $number, ?int $decimals = null): float
    {
        $result = (float) bcmul((string) $this->getValue(), (string) $number->getValue(), Number::BC_MATH_ROUNDING);

        if (null !== $decimals) {
            return round($result, $decimals);
        }

        return $result;
    }

    public function divide(Number $number, ?int $decimals = null): float
    {
        $result = (float) bcdiv((string) $this->getValue(), (string) $number->getValue(), Number::BC_MATH_ROUNDING);

        if (null !== $decimals) {
            return round($result, $decimals);
        }

        return $result;
    }

    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}