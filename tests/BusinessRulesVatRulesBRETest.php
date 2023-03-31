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

class BusinessRulesVatRulesBRETest extends TestCase
{
    /**
     * @test
     * @testdox BR-E-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Exempt from VAT", the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrE5Success
     */
    public function brE5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrE5Success(): \Generator
    {
        yield 'BR-E-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-E-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Exempt from VAT", the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrE5Error
     */
    public function brE5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, $invoicedItemVatRate);
    }

    public static function provideBrE5Error(): \Generator
    {
        yield 'BR-E-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-E-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-E-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-E-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Exempt from VAT", the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrE6Success
     */
    public function brE6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrE6Success(): \Generator
    {
        yield 'BR-E-6 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-E-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Exempt from VAT", the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrE6Error
     */
    public function brE6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrE6Error(): \Generator
    {
        yield 'BR-E-6 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-E-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-E-6 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-E-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Exempt from VAT", the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrE7Success
     */
    public function brE7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrE7Success(): \Generator
    {
        yield 'BR-E-7 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-E-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Exempt from VAT", the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrE7Error
     */
    public function brE7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrE7Error(): \Generator
    {
        yield 'BR-E-7 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-E-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-E-7 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-E-9 : The VAT category tax amount (BT-117) In a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) equals "Exempt from VAT" shall equal 0 (zero).
     */
    public function brE9_success(): void
    {
        $vatBreakdown = new VatBreakdown(1000, 0, VatCategory::EXEMPT_FROM_TAX, 0);

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    /**
     * @test
     * @testdox BR-E-9 : The VAT category tax amount (BT-117) In a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) equals "Exempt from VAT" shall equal 0 (zero).
     * @dataProvider provideBrE9Error
     */
    public function brE9_error(float $vatCategoryTaxAmount): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(1000, $vatCategoryTaxAmount, VatCategory::EXEMPT_FROM_TAX, 0);
    }

    public static function provideBrE9Error(): \Generator
    {
        yield 'BR-E-9 Error #1' => [
            'vatCategoryTaxAmount' => 10,
        ];
        yield 'BR-E-9 Error #2' => [
            'vatCategoryTaxAmount' => -10,
        ];
    }
}
