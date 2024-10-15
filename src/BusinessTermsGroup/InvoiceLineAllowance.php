<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\Codelist\AllowanceReasonCodeUNTDID5189;
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
    private ?AllowanceReasonCodeUNTDID5189 $reasonCode;

    public function __construct(float $amount, ?string $reason = null, ?AllowanceReasonCodeUNTDID5189 $reasonCode = null)
    {
        if ((!is_string($reason) || empty($reason)) && !$reasonCode instanceof AllowanceReasonCodeUNTDID5189) {
            throw new \Exception('@todo');
        }

        $this->amount = new Amount($amount);
        $this->baseAmount = null;
        $this->percentage = null;
        $this->reason = $reason;
        $this->reasonCode = $reasonCode;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getBaseAmount(): ?Amount
    {
        return $this->baseAmount;
    }

    public function setBaseAmount(?float $baseAmount): self
    {
        $this->baseAmount = \is_float($baseAmount) ? new Amount($baseAmount) : null;

        return $this;
    }

    public function getPercentage(): ?Percentage
    {
        return $this->percentage;
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

    public function getReasonCode(): ?AllowanceReasonCodeUNTDID5189
    {
        return $this->reasonCode;
    }
}
