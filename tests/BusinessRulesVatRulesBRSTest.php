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
use Tiime\EN16931\DataType\AllowanceReasonCode;
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
     * @dataProvider provideBrS1Success
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brS1_success(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrS1Success(): \Generator
    {
        yield 'BR-S-1 Success #1' => [
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
                    new InvoiceLineIdentifier("A2"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    2000,
                    new PriceDetails(2000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(3000, 600, VatCategory::STANDARD, 20)
            ],
            'documentTotals' => new DocumentTotals(
                3000,
                3000,
                3600,
                3600,
                invoiceTotalVatAmount: 600,
            )
        ];
        yield 'BR-S-1 Success #2' => [
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
            ],
            'documentLevelAllowances' => [
                new DocumentLevelAllowance(100, VatCategory::STANDARD, reasonCode: AllowanceReasonCode::STANDARD, vatRate: 20)
            ],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(1000, 200, VatCategory::STANDARD, 20)
            ],
            'documentTotals' => new DocumentTotals(
                1000,
                900,
                1100,
                1100,
                invoiceTotalVatAmount: 200,
                sumOfAllowancesOnDocumentLevel: 100,
            )
        ];
        yield 'BR-S-1 Success #3' => [
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
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [
                new DocumentLevelCharge(100, VatCategory::STANDARD, 'Hoobastank', vatRate: 20)
            ],
            'vatBreakdowns' => [
                new VatBreakdown(1000, 200, VatCategory::STANDARD, 20)
            ],
            'documentTotals' => new DocumentTotals(
                1000,
                1100,
                1300,
                1300,
                invoiceTotalVatAmount: 200,
                sumOfChargesOnDocumentLevel: 100,
            )
        ];
    }

    /**
     * @test
     * @testdox BR-S-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT102) is “Standard rated” shall
     * contain in the VAT breakdown (BG-23) at least one VAT category code (BT-118) equal with "Standard rated".
     * @dataProvider provideBrS1Error
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brS1_error(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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

    public static function provideBrS1Error(): \Generator
    {
        yield 'BR-S-1 Error #1' => [
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
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [],
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
     * @testdox BR-S-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax registration
     * identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS2(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-S-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS3(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-S-4 : An Invoice that contains a Document level charge (BG-21) where the Document level charge VAT
     * category code (BT-102) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS4(): void
    {
        $this->markTestSkipped('@todo');
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
     * @dataProvider provideBrS6Success
     */
    public function brS6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::STANDARD, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrS6Success(): \Generator
    {
        yield 'BR-S-6 Success #1' => [
            'vatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-S-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Standard rated" the Document level allowance VAT rate (BT96) shall be greater than zero.
     * @dataProvider provideBrS6Error
     */
    public function brS6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::STANDARD, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrS6Error(): \Generator
    {
        yield 'BR-S-6 Error #1' => [
            'vatRate' => 0,
        ];
        yield 'BR-S-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-S-6 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-S-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Standard rated" the Document level charge VAT rate (BT-103) shall be greater than zero.
     * @dataProvider provideBrS7Success
     */
    public function brS7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::STANDARD, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrS7Success(): \Generator
    {
        yield 'BR-S-7 Success #1' => [
            'vatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-S-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Standard rated" the Document level charge VAT rate (BT-103) shall be greater than zero.
     * @dataProvider provideBrS7Error
     */
    public function brS7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::STANDARD, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrS7Error(): \Generator
    {
        yield 'BR-S-7 Error #1' => [
            'vatRate' => 0,
        ];
        yield 'BR-S-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-S-7 Error #3' => [
            'vatRate' => null,
        ];
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
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-S-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where VAT category code
     * (BT-118) is "Standard rated" shall equal the VAT category taxable amount (BT-116) multiplied by the VAT
     * category rate (BT-119).
     */
    public function brS9(): void
    {
        $this->assertTrue(true, 'Same as BR-CO-17');
    }

    /**
     * @test
     * @testdox BR-S-10 : A VAT Breakdown (BG-23) with VAT Category code (BT-118) "Standard rate" shall not have a VAT
     * exemption reason code (BT-121) or VAT exemption reason text (BT-120).
     */
    public function brS10(): void
    {
        $this->markTestSkipped('@todo');
    }
}
