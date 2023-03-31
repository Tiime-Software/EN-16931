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

class BusinessRulesVatRulesBROTest extends TestCase
{
    /**
     * @test
     * @testdox BR-O-5 : An Invoice line (BG-25) where the VAT category code (BT-151) is "Not subject to VAT" shall not
     * contain an Invoiced item VAT rate (BT-152).
     */
    public function brO5_success(): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    /**
     * @test
     * @testdox BR-O-5 : An Invoice line (BG-25) where the VAT category code (BT-151) is "Not subject to VAT" shall not
     * contain an Invoiced item VAT rate (BT-152).
     * @dataProvider provideBrO5Error
     */
    public function brO5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, $invoicedItemVatRate);
    }

    public static function provideBrO5Error(): \Generator
    {
        yield 'BR-O-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-O-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-O-5 Error #3' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-O-6 : A Document level allowance (BG-20) where VAT category code (BT-95) is "Not subject to VAT"
     * shall not contain a Document level allowance VAT rate (BT-96).
     */
    public function brO6_success(): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank');

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    /**
     * @test
     * @testdox BR-O-6 : A Document level allowance (BG-20) where VAT category code (BT-95) is "Not subject to VAT"
     * shall not contain a Document level allowance VAT rate (BT-96).
     * @dataProvider provideBrO6Error
     */
    public function brO6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrO6Error(): \Generator
    {
        yield 'BR-O-6 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-O-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-O-6 Error #3' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-O-7 : A Document level charge (BG-21) where the VAT category code (BT-102) is "Not subject to VAT"
     * shall not contain a Document level charge VAT rate (BT-103).
     */
    public function brO7_success(): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank');

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    /**
     * @test
     * @testdox BR-O-7 : A Document level charge (BG-21) where the VAT category code (BT-102) is "Not subject to VAT"
     * shall not contain a Document level charge VAT rate (BT-103).
     * @dataProvider provideBrO7Error
     */
    public function brO7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrO7Error(): \Generator
    {
        yield 'BR-O-7 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-O-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-O-7 Error #3' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-O-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) is “Not subject to VAT” shall be 0 (zero).
     */
    public function brO9_success(): void
    {
        $vatBreakdown = new VatBreakdown(1000, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX);

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    /**
     * @test
     * @testdox BR-O-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) is “Not subject to VAT” shall be 0 (zero).
     * @dataProvider provideBrO9Error
     */
    public function brO9_error(float $vatCategoryTaxAmount): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(1000, $vatCategoryTaxAmount, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX);
    }

    public static function provideBrO9Error(): \Generator
    {
        yield 'BR-O-9 Error #1' => [
            'vatCategoryTaxAmount' => 10,
        ];
        yield 'BR-O-9 Error #2' => [
            'vatCategoryTaxAmount' => -10,
        ];
    }
}
