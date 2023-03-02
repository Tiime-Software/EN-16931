<?php

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\ChargeReasonCode;

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
    private float $amount;

    /**
     * BT-142
     * The base amount that may be used, in conjunction with the Invoice line charge percentage,
     * to calculate the Invoice line charge amount.
     */
    private ?float $baseAmount;

    /**
     * BT-143
     * The percentage that may be used, in conjunction with the Invoice line charge base amount,
     * to calculate the Invoice line charge amount.
     */
    private ?float $percentage;

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
        if (!is_string($reason) && !$reasonCode instanceof ChargeReasonCode) {
            throw new \Exception('@todo');
        }

        $this->amount = $amount;
        $this->baseAmount = null;
        $this->percentage = null;
        $this->reason = null;
        $this->reasonCode = null;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBaseAmount(): ?float
    {
        return $this->baseAmount;
    }

    public function setBaseAmount(?float $baseAmount): self
    {
        $this->baseAmount = $baseAmount;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(?float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        if (!is_string($reason) && !$this->reasonCode instanceof ChargeReasonCode) {
            throw new \Exception('@todo');
        }

        $this->reason = $reason;

        return $this;
    }

    public function getReasonCode(): ?ChargeReasonCode
    {
        return $this->reasonCode;
    }

    public function setReasonCode(?ChargeReasonCode $reasonCode): self
    {
        if (!is_string($this->reason) && !$reasonCode instanceof ChargeReasonCode) {
            throw new \Exception('@todo');
        }

        $this->reasonCode = $reasonCode;

        return $this;
    }
}
