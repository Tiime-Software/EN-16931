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

class BusinessRulesVatRulesBRAETest extends TestCase
{
    /**
     * @test
     * @testdox BR-AE-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Reverse charge" the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrAE5Success
     */
    public function brAE5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::VAT_REVERSE_CHARGE, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrAE5Success(): \Generator
    {
        yield 'BR-AE-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-AE-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Reverse charge" the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrAE5Error
     */
    public function brAE5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::VAT_REVERSE_CHARGE, $invoicedItemVatRate);
    }

    public static function provideBrAE5Error(): \Generator
    {
        yield 'BR-AE-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-AE-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-AE-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-AE-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Reverse charge" the Document level allowance VAT rate (BT96) shall be 0 (zero).
     * @dataProvider provideBrAE6Success
     */
    public function brAE6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::VAT_REVERSE_CHARGE, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrAE6Success(): \Generator
    {
        yield 'BR-AE-6 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-AE-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Reverse charge" the Document level allowance VAT rate (BT96) shall be 0 (zero).
     * @dataProvider provideBrAE6Error
     */
    public function brAE6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::VAT_REVERSE_CHARGE, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrAE6Error(): \Generator
    {
        yield 'BR-AE-6 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-AE-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-AE-6 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-AE-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Reverse charge" the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrAE7Success
     */
    public function brAE7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::VAT_REVERSE_CHARGE, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrAE7Success(): \Generator
    {
        yield 'BR-AE-7 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-AE-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Reverse charge" the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrAE7Error
     */
    public function brAE7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::VAT_REVERSE_CHARGE, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrAE7Error(): \Generator
    {
        yield 'BR-AE-7 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-AE-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-AE-7 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-AE-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) is “Reverse charge” shall be 0 (zero).
     */
    public function brAE9_success(): void
    {
        $vatBreakdown = new VatBreakdown(1000, 0, VatCategory::VAT_REVERSE_CHARGE, 0);

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    /**
     * @test
     * @testdox BR-AE-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) is “Reverse charge” shall be 0 (zero).
     * @dataProvider provideBrAE9Error
     */
    public function brAE9_error(float $vatCategoryTaxAmount): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(1000, $vatCategoryTaxAmount, VatCategory::VAT_REVERSE_CHARGE, 0);
    }

    public static function provideBrAE9Error(): \Generator
    {
        yield 'BR-AE-9 Error #1' => [
            'vatCategoryTaxAmount' => 10,
        ];
        yield 'BR-AE-9 Error #2' => [
            'vatCategoryTaxAmount' => -10,
        ];
    }
}
