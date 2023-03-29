<?php

namespace Tests\Tiime\EN16931;

use PHPUnit\Framework\TestCase;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\BuyerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\DocumentTotals;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLine;
use Tiime\EN16931\BusinessTermsGroup\ItemInformation;
use Tiime\EN16931\BusinessTermsGroup\LineVatInformation;
use Tiime\EN16931\BusinessTermsGroup\PriceDetails;
use Tiime\EN16931\BusinessTermsGroup\ProcessControl;
use Tiime\EN16931\BusinessTermsGroup\Seller;
use Tiime\EN16931\BusinessTermsGroup\SellerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\VatBreakdown;
use Tiime\EN16931\DataType\CountryAlpha2Code;
use Tiime\EN16931\DataType\CurrencyCode;
use Tiime\EN16931\DataType\Identifier\InvoiceIdentifier;
use Tiime\EN16931\DataType\Identifier\InvoiceLineIdentifier;
use Tiime\EN16931\DataType\Identifier\SellerIdentifier;
use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;
use Tiime\EN16931\DataType\InternationalCodeDesignator;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\Invoice;

class BusinessRulesVatRulesBRICTest extends TestCase
{
    /**
     * @test
     * @testdox BR-IC-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Intracommunity supply" the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrIC5Success
     */
    public function brIC5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrIC5Success(): \Generator
    {
        yield 'BR-IC-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-IC-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Intracommunity supply" the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrIC5Error
     */
    public function brIC5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES, $invoicedItemVatRate);
    }

    public static function provideBrIC5Error(): \Generator
    {
        yield 'BR-IC-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-IC-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-IC-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }
}
