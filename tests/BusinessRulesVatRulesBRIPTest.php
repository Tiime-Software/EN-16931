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

class BusinessRulesVatRulesBRIPTest extends TestCase
{
    /**
     * @test
     * @testdox BR-IP-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT-102) is “IPSI” shall contain in
     * the VAT breakdown (BG-23) at least one VAT category code (BT118) equal with "IPSI".
     * @dataProvider provideBrIP1Success
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brIP1_success(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotal,
            $vatBreakdowns,
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            $documentLevelAllowances,
            $documentLevelCharges
        );

        $this->assertInstanceOf(Invoice::class,  $invoice);
    }

    public static function provideBrIP1Success(): \Generator
    {
        yield 'BR-IP-1 Success #1' => [
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 0),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(2000, 400, VatCategory::STANDARD, 20),
                new VatBreakdown(2000, 0, VatCategory::CEUTA_AND_MELILLA, 0)
            ],
            'documentTotals' => new DocumentTotals(
                2000,
                2000,
                2400,
                2400,
                invoiceTotalVatAmount: 400,
            )
        ];
    }

    /**
     * @test
     * @testdox BR-IP-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT-102) is “IPSI” shall contain in
     * the VAT breakdown (BG-23) at least one VAT category code (BT118) equal with "IPSI".
     * @dataProvider provideBrIP1Error
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brIP1_error(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotal,
            $vatBreakdowns,
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            $documentLevelAllowances,
            $documentLevelCharges
        );
    }

    public static function provideBrIP1Error(): \Generator
    {
        yield 'BR-IP-1 Error #1' => [
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 0),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(2000, 400, VatCategory::STANDARD, 20)
            ],
            'documentTotals' => new DocumentTotals(
                2000,
                2000,
                2400,
                2400,
                invoiceTotalVatAmount: 400,
            )
        ];
    }

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
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: $vatRate);

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

        new DocumentLevelAllowance(1, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: $vatRate);
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

    /**
     * @test
     * @testdox BR-IP-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "IPSI" the Document level charge VAT rate (BT-103) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP7Success
     */
    public function brIP7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrIP7Success(): \Generator
    {
        yield 'BR-IP-7 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
        yield 'BR-IP-7 Success #2' => [
            'invoicedItemVatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "IPSI" the Document level charge VAT rate (BT-103) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP7Error
     */
    public function brIP7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrIP7Error(): \Generator
    {
        yield 'BR-IP-7 Error #1' => [
            'vatRate' => -10,
        ];
        yield 'BR-IP-7 Error #2' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where VAT category code
     * (BT-118) is "IPSI" shall equal the VAT category taxable amount (BT-116) multiplied by the VAT category rate
     * (BT-119).
     */
    public function brIP9(): void
    {
        $this->assertTrue(true, 'Same as BR-CO-17');
    }
}
