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

class BusinessRulesVatRulesBRZTest extends TestCase
{
    /**
     * @test
     * @testdox BR-Z-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is "Zero rated"
     * the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrZ5Success
     */
    public function brZ5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::ZERO_RATED_GOODS, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrZ5Success(): \Generator
    {
        yield 'BR-Z-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-Z-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is "Zero rated"
     * the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrZ5Error
     */
    public function brZ5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0);
    }

    public static function provideBrZ5Error(): \Generator
    {
        yield 'BR-Z-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-Z-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-Z-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }
}
