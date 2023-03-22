<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\ChargeReasonCode;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\Percentage;

/**
 * BG-21
 * A group of business terms providing information about charges and taxes other than VAT,
 * applicable to the Invoice as a whole.
 */
class DocumentLevelCharge
{
    /**
     * BT-99
     * The amount of a charge, without VAT.
     */
    private Amount $amount;

    /**
     * BT-100
     * The base amount that may be used, in conjunction with the document level charge percentage,
     * to calculate the document level charge amount.
     */
    private ?Amount $baseAmount;

    /**
     * BT-101
     * The percentage that may be used, in conjunction with the document level charge base amount,
     * to calculate the document level charge amount.
     */
    private ?Percentage $percentage;

    /**
     * BT-102
     * A coded identification of what VAT category applies to the document level charge.
     */
    private VatCategory $vatCategoryCode;

    /**
     * BT-103
     * The VAT rate, represented as percentage that applies to the document level charge.
     */
    private ?Percentage $vatRate;

    /**
     * BT-104
     * The reason for the document level charge, expressed as text.
     */
    private ?string $reason;

    /**
     * BT-105
     * The reason for the document level charge, expressed as a code.
     */
    private ?ChargeReasonCode $reasonCode;

    public function __construct(
        float $amount,
        VatCategory $vatCategoryCode,
        ?string $reason = null,
        ?ChargeReasonCode $reasonCode = null
    ) {
        if ((!is_string($reason) || empty($reason)) && !$reasonCode instanceof ChargeReasonCode) {
            throw new \Exception('@todo');
        }

        $this->amount = new Amount($amount);
        $this->baseAmount = null;
        $this->percentage = null;
        $this->vatCategoryCode = $vatCategoryCode;
        $this->vatRate = null;
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
        return $this->vatRate?->getValueRounded();
    }

    public function setVatRate(?float $vatRate): self
    {
        $this->vatRate = \is_float($vatRate) ? new Percentage($vatRate) : null;

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
