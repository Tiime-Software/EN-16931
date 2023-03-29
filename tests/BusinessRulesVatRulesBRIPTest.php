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

class BusinessRulesVatRulesBRIPTest extends TestCase
{
    /**
     * @test
     * @testdox BR-IP-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is "IPSI" the
     * Invoiced item VAT rate (BT-152) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP5Success
     */
    public function brIP5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrIP5Success(): \Generator
    {
        yield 'BR-IP-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
        yield 'BR-IP-5 Success #2' => [
            'invoicedItemVatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is "IPSI" the
     * Invoiced item VAT rate (BT-152) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP5Error
     */
    public function brIP5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, $invoicedItemVatRate);
    }

    public static function provideBrIP5Error(): \Generator
    {
        yield 'BR-IP-5 Error #1' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-IP-5 Error #2' => [
            'invoicedItemVatRate' => null,
        ];
    }
















    /**
     * @test
     * @testdox BR-IP-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "IPSI" the Document level allowance VAT rate (BT-96) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP6Success
     */
    public function brIP6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::CANARY_ISLANDS, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrIP6Success(): \Generator
    {
        yield 'BR-IP-6 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
        yield 'BR-IP-6 Success #2' => [
            'invoicedItemVatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "IPSI" the Document level allowance VAT rate (BT-96) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP6Error
     */
    public function brIP6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::CANARY_ISLANDS, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrIP6Error(): \Generator
    {
        yield 'BR-IP-6 Error #1' => [
            'vatRate' => -10,
        ];
        yield 'BR-IP-6 Error #2' => [
            'vatRate' => null,
        ];
    }
}
