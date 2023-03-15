<?php

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\AllowanceReasonCode;
use Tiime\EN16931\DataType\VatCategory;

/**
 * BG-20
 * A group of business terms providing information about allowances applicable to the Invoice as a whole.
 */
class DocumentLevelAllowance
{
    /**
     * BT-92
     * The amount of an allowance, without VAT.
     */
    private float $amount;

    /**
     * BT-93
     * The base amount that may be used, in conjunction with the document level allowance percentage,
     * to calculate the document level allowance amount.
     */
    private ?float $baseAmount;

    /**
     * BT-94
     * The percentage that may be used, in conjunction with the document level allowance base amount,
     * to calculate the document level allowance amount.
     */
    private ?float $percentage;

    /**
     * BT-95
     * A coded identification of what VAT category applies to the document level allowance.
     */
    private VatCategory $vatCategoryCode;

    /**
     * BT-96
     * The VAT rate, represented as percentage that applies to the document level allowance.
     */
    private ?float $vatRate;

    /**
     * BT-97
     * The reason for the document level allowance, expressed as text.
     */
    private ?string $reason;

    /**
     * BT-98
     * The reason for the document level allowance, expressed as a code.
     */
    private ?AllowanceReasonCode $reasonCode;

    public function __construct(
        float $amount,
        VatCategory $vatCategoryCode,
        ?string $reason = null,
        ?AllowanceReasonCode $reasonCode = null
    ) {
        if ((!is_string($reason) || empty($reason)) && !$reasonCode instanceof AllowanceReasonCode) {
            throw new \Exception('@todo');
        }

        $this->amount = $amount;
        $this->baseAmount = null;
        $this->percentage = null;
        $this->vatCategoryCode = $vatCategoryCode;
        $this->vatRate = null;
        $this->reason = $reason;
        $this->reasonCode = $reasonCode;
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

    public function getVatCategoryCode(): VatCategory
    {
        return $this->vatCategoryCode;
    }

    public function setVatCategoryCode(VatCategory $vatCategoryCode): self
    {
        $this->vatCategoryCode = $vatCategoryCode;

        return $this;
    }

    public function getVatRate(): ?float
    {
        return $this->vatRate;
    }

    public function setVatRate(?float $vatRate): self
    {
        $this->vatRate = $vatRate;

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
