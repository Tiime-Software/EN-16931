<?php

declare(strict_types=1);

namespace Tiime\EN16931\SemanticDataType;

interface Number
{
    public const int BC_MATH_ROUNDING = 20;

    public function getValue(): float;

    public function add(Number $number, ?int $decimals = null): float;

    public function subtract(Number $number, ?int $decimals = null): float;

    public function multiply(Number $number, ?int $decimals = null): float;

    public function divide(Number $number, ?int $decimals = null): float;

    public function __toString(): string;
}
