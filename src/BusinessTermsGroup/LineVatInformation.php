<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\SemanticDataType\Percentage;

/**
 * BG-30
 * A group of business terms providing information about the VAT applicable for
 * the goods and services invoiced on the Invoice line.
 */
class LineVatInformation
{
    /**
     * BT-151
     * The VAT category code for the invoiced item.
     *
     * Code de type de TVA applicable à l'article facturé.
     */
    private VatCategory $invoicedItemVatCategoryCode;

    /**
     * BT-152
     * The VAT rate, represented as percentage that applies to the invoiced item.
     */
    private ?Percentage $invoicedItemVatRate;

    public function __construct(
        VatCategory $invoicedItemVatCategoryCode,
        ?float $invoicedItemVatRate = null,
    ) {
        if (
            $invoicedItemVatCategoryCode === VatCategory::STANDARD_RATE
            && (null === $invoicedItemVatRate || $invoicedItemVatRate <= 0.0)
        ) {
            throw new \Exception('@todo : BR-genericVAT-5');
        }

        if (
            in_array(
                $invoicedItemVatCategoryCode,
                [
                    VatCategory::ZERO_RATED_GOODS,
                    VatCategory::EXEMPT_FROM_TAX,
                    VatCategory::VAT_REVERSE_CHARGE,
                    VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES,
                    VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED,
                ]
            )
            && $invoicedItemVatRate !== 0.0
        ) {
            throw new \Exception('@todo : BR-genericVAT-5');
        }

        if (
            $invoicedItemVatCategoryCode === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX
            && null !== $invoicedItemVatRate
        ) {
            throw new \Exception('@todo : BR-genericVAT-5');
        }

        if (
            in_array($invoicedItemVatCategoryCode, [
                VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX,
                VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA
            ])
            && ($invoicedItemVatRate < 0.0 || null === $invoicedItemVatRate)
        ) {
            throw new \Exception('@todo : BR-genericVAT-5');
        }

        $this->invoicedItemVatCategoryCode = $invoicedItemVatCategoryCode;
        $this->invoicedItemVatRate = is_float($invoicedItemVatRate) ?
            new Percentage($invoicedItemVatRate) : $invoicedItemVatRate;
    }

    public function getInvoicedItemVatCategoryCode(): VatCategory
    {
        return $this->invoicedItemVatCategoryCode;
    }

    public function getInvoicedItemVatRate(): ?Percentage
    {
        return $this->invoicedItemVatRate;
    }
}
