<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\ChargeReasonCode;
use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\Percentage;

/**
 * BG-28
 * A group of business terms providing information about charges and taxes other than VAT applicable to
 * the individual Invoice line.
 */
class InvoiceLineCharge
{
    /**
     * BT-141
     * The amount of a charge, without VAT.
     */
    private Amount $amount;

    /**
     * BT-142
     * The base amount that may be used, in conjunction with the Invoice line charge percentage,
     * to calculate the Invoice line charge amount.
     */
    private ?Amount $baseAmount;

    /**
     * BT-143
     * The percentage that may be used, in conjunction with the Invoice line charge base amount,
     * to calculate the Invoice line charge amount.
     */
    private ?Percentage $percentage;

    /**
     * BT-144
     * The reason for the Invoice line charge, expressed as text.
     */
    private ?string $reason;

    /**
     * BT-145
     * The reason for the Invoice line charge, expressed as a code.
     */
    private ?ChargeReasonCode $reasonCode;

    public function __construct(float $amount, ?string $reason = null, ?ChargeReasonCode $reasonCode = null)
    {
        if ((!is_string($reason) || empty($reason)) && !$reasonCode instanceof ChargeReasonCode) {
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

    public function getReasonCode(): ?ChargeReasonCode
    {
        return $this->reasonCode;
    }
}
