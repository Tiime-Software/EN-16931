<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\AllowanceReasonCode;
use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\Percentage;

/**
 * BG-27
 * A group of business terms providing information about allowances applicable to the individual Invoice line.
 */
class InvoiceLineAllowance
{
    /**
     * BT-136
     * The amount of an allowance, without VAT.
     */
    private Amount $amount;

    /**
     * BT-137
     * The base amount that may be used, in conjunction with the Invoice line allowance percentage,
     * to calculate the Invoice line allowance amount.
     */
    private ?Amount $baseAmount;

    /**
     * BT-138
     * The percentage that may be used, in conjunction with the Invoice line allowance base amount,
     * to calculate the Invoice line allowance amount.
     */
    private ?Percentage $percentage;

    /**
     * BT-139
     * The reason for the Invoice line allowance, expressed as text.
     */
    private ?string $reason;

    /**
     * BT-140
     * The reason for the Invoice line allowance, expressed as a code.
     */
    private ?AllowanceReasonCode $reasonCode;

    public function __construct(float $amount, ?string $reason = null, ?AllowanceReasonCode $reasonCode = null)
    {
        if ((!is_string($reason) || empty($reason)) && !$reasonCode instanceof AllowanceReasonCode) {
            throw new \Exception('@todo');
        }

        $this->amount = new Amount($amount);
        $this->baseAmount = null;
        $this->percentage = null;
        $this->reason = $reason;
        $this->reasonCode = $reasonCode;
    }

    public function getAmount(): float
    {
        return $this->amount->getValueRounded();
    }

    public function setAmount(float $amount): self
    {
        $this->amount = new Amount($amount);

        return $this;
    }

    public function getBaseAmount(): ?float
    {
        return $this->baseAmount?->getValueRounded();
    }

    public function setBaseAmount(?float $baseAmount): self
    {
        $this->baseAmount = \is_float($baseAmount) ? new Amount($baseAmount) : null;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage?->getValueRounded();
    }

    public function setPercentage(?float $percentage): self
    {
        $this->percentage = \is_float($percentage) ? new Percentage($percentage) : null;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getReasonCode(): ?AllowanceReasonCode
    {
        return $this->reasonCode;
    }
}
