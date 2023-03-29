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

class BusinessRulesVatRulesBRSTest extends TestCase
{
    /**
     * @test
     * @testdox BR-S-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT102) is “Standard rated” shall
     * contain in the VAT breakdown (BG-23) at least one VAT category code (BT-118) equal with "Standard rated".
     */
    public function brS1(): void
    {

    }

    /**
     * @test
     * @testdox BR-S-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax registration
     * identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS2(): void
    {

    }

    /**
     * @test
     * @testdox BR-S-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS3(): void
    {

    }

    /**
     * @test
     * @testdox BR-S-4 : An Invoice that contains a Document level charge (BG-21) where the Document level charge VAT
     * category code (BT-102) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS4(): void
    {

    }

    /**
     * @test
     * @testdox BR-S-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Standard rated" the Invoiced item VAT rate (BT-152) shall be greater than zero.
     * @dataProvider provideBrS5Success
     */
    public function brS5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::STANDARD, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrS5Success(): \Generator
    {
        yield 'BR-S-5 Success #1' => [
            'invoicedItemVatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-S-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Standard rated" the Invoiced item VAT rate (BT-152) shall be greater than zero.
     * @dataProvider provideBrS5Error
     */
    public function brS5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::STANDARD, $invoicedItemVatRate);
    }

    public static function provideBrS5Error(): \Generator
    {
        yield 'BR-S-5 Error #1' => [
            'invoicedItemVatRate' => 0,
        ];
        yield 'BR-S-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-S-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-S-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Standard rated" the Document level allowance VAT rate (BT96) shall be greater than zero.
     */
    public function brS6(): void
    {

    }

    /**
     * @test
     * @testdox BR-S-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Standard rated" the Document level charge VAT rate (BT-103) shall be greater than zero.
     */
    public function brS7(): void
    {

    }

    /**
     * @test
     * @testdox BR-S-8 : For each different value of VAT category rate (BT-119) where the VAT category code (BT-118) is
     * "Standard rated", the VAT category taxable amount (BT-116) in a VAT breakdown (BG-23) shall equal the sum of
     * Invoice line net amounts (BT-131) plus the sum of document level charge amounts (BT-99) minus the sum of
     * document level allowance amounts (BT-92) where the VAT category code (BT-151, BT-102, BT-95) is "Standard rated"
     * and the VAT rate (BT-152, BT-103, BT-96) equals the VAT category rate (BT-119).
     */
    public function brS8(): void
    {

    }

    /**
     * @test
     * @testdox BR-S-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where VAT category code
     * (BT-118) is "Standard rated" shall equal the VAT category taxable amount (BT-116) multiplied by the VAT
     * category rate (BT-119).
     */
    public function brS9(): void
    {

    }

    /**
     * @test
     * @testdox BR-S-10 : A VAT Breakdown (BG-23) with VAT Category code (BT-118) "Standard rate" shall not have a VAT
     * exemption reason code (BT-121) or VAT exemption reason text (BT-120).
     */
    public function brS10(): void
    {

    }
}
