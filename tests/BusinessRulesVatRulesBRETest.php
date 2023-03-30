<?php

namespace Tests\Tiime\EN16931;

use PHPUnit\Framework\TestCase;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\BuyerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelAllowance;
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
}
