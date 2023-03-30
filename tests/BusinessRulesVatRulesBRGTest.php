<?php

namespace Tests\Tiime\EN16931;

use PHPUnit\Framework\TestCase;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\BuyerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelAllowance;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelCharge;
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

class BusinessRulesVatRulesBRGTest extends TestCase
{
    /**
     * @test
     * @testdox BR-G-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Export outside the EU" the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrG5Success
     */
    public function brG5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrG5Success(): \Generator
    {
        yield 'BR-G-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-G-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Export outside the EU" the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrG5Error
     */
    public function brG5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, $invoicedItemVatRate);
    }

    public static function provideBrG5Error(): \Generator
    {
        yield 'BR-G-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-G-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-G-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-G-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Export outside the EU" the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrG6Success
     */
    public function brG6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrG6Success(): \Generator
    {
        yield 'BR-G-6 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-G-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Export outside the EU" the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrG6Error
     */
    public function brG6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrG6Error(): \Generator
    {
        yield 'BR-G-6 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-G-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-G-6 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-G-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Export outside the EU" the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrG7Success
     */
    public function brG7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrG7Success(): \Generator
    {
        yield 'BR-G-7 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-G-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Export outside the EU" the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrG7Error
     */
    public function brG7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrG7Error(): \Generator
    {
        yield 'BR-G-7 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-G-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-G-7 Error #3' => [
            'vatRate' => null,
        ];
    }
}
