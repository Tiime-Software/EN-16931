<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\AllowanceReasonCode;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\Percentage;

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
    private Amount $amount;

    /**
     * BT-93
     * The base amount that may be used, in conjunction with the document level allowance percentage,
     * to calculate the document level allowance amount.
     */
    private ?Amount $baseAmount;

    /**
     * BT-94
     * The percentage that may be used, in conjunction with the document level allowance base amount,
     * to calculate the document level allowance amount.
     */
    private ?Percentage $percentage;

    /**
     * BT-95
     * A coded identification of what VAT category applies to the document level allowance.
     */
    private VatCategory $vatCategoryCode;

    /**
     * BT-96
     * The VAT rate, represented as percentage that applies to the document level allowance.
     */
    private ?Percentage $vatRate;

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
        ?AllowanceReasonCode $reasonCode = null,
        ?float $vatRate = null
    ) {
        if ((!is_string($reason) || empty($reason)) && !$reasonCode instanceof AllowanceReasonCode) {
            throw new \Exception('@todo');
        }

        if (
            $vatCategoryCode === VatCategory::STANDARD
            && (null === $vatRate || $vatRate <= 0.0)
        ) {
            throw new \Exception('@todo : BR-genericVAT-6');
        }

        if (
            in_array(
                $vatCategoryCode,
                [
                    VatCategory::ZERO_RATED_GOODS,
                    VatCategory::EXEMPT_FROM_TAX,
                    VatCategory::VAT_REVERSE_CHARGE,
                    VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES,
                    VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED,
                ]
            )
            && $vatRate !== 0.0
        ) {
            throw new \Exception('@todo : BR-genericVAT-6');
        }

        if (
            $vatCategoryCode === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX
            && null !== $vatRate
        ) {
            throw new \Exception('@todo : BR-genericVAT-6');
        }

        if (
            in_array($vatCategoryCode, [VatCategory::CANARY_ISLANDS, VatCategory::CEUTA_AND_MELILLA])
            && ($vatRate < 0.0 || null === $vatRate)
        ) {
            throw new \Exception('@todo : BR-genericVAT-6');
        }

        $this->vatRate = $vatRate !== null ? new Percentage($vatRate) : $vatRate;
        $this->amount = new Amount($amount);
        $this->baseAmount = null;
        $this->percentage = null;
        $this->vatCategoryCode = $vatCategoryCode;
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

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getReasonCode(): ?AllowanceReasonCode
    {
        return $this->reasonCode;
    }
}
