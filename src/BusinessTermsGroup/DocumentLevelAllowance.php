<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\Codelist\AllowanceReasonCodeUNTDID5189;
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
    private ?AllowanceReasonCodeUNTDID5189 $reasonCode;

    public function __construct(
        float $amount,
        VatCategory $vatCategoryCode,
        ?string $reason = null,
        ?AllowanceReasonCodeUNTDID5189 $reasonCode = null,
        ?float $vatRate = null
    ) {
        if ((!is_string($reason) || empty($reason)) && !$reasonCode instanceof AllowanceReasonCodeUNTDID5189) {
            throw new \Exception('@todo');
        }

        if (
            $vatCategoryCode === VatCategory::STANDARD_RATE
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
            in_array($vatCategoryCode, [
                VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX,
                VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA
            ])
            && ($vatRate < 0.0 || null === $vatRate)
        ) {
            throw new \Exception('@todo : BR-genericVAT-6');
        }

        $this->vatRate = is_float($vatRate) ? new Percentage($vatRate) : $vatRate;
        $this->amount = new Amount($amount);
        $this->baseAmount = null;
        $this->percentage = null;
        $this->vatCategoryCode = $vatCategoryCode;
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

    public function getVatCategoryCode(): VatCategory
    {
        return $this->vatCategoryCode;
    }

    public function getVatRate(): ?Percentage
    {
        return $this->vatRate;
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
