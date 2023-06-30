<?php

declare(strict_types=1);

namespace Tiime\EN16931\SemanticDataType;

class DecimalNumber implements Number
{
    public function __construct(private readonly float $value, private readonly ?int $decimals = null)
    {
        if (
            $this->decimals !== null
            && !preg_match(sprintf('/^-?\d+(\.\d{1,%s})?$/', $decimals), (string) $value)
        ) {
            throw new \Exception('@todo');
        }
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getValueRounded(): float
    {
        if (null === $this->decimals) {
            return $this->value;
        }

        return round($this->value, $this->decimals);
    }

    public function getFormattedValueRounded(string $decimal_separator = '.', string $thousands_separator = ''): string
    {
        if (null === $this->decimals) {
            return number_format($this->value, 0, $decimal_separator, $thousands_separator);
        }

        return number_format(round($this->value, $this->decimals), $this->decimals, $decimal_separator, $thousands_separator);
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
        return (string) $this->getValueRounded();
    }
}
