<?php

namespace Tests\Tiime\EN16931;

use PHPUnit\Framework\TestCase;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\BuyerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\DocumentTotals;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLine;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLinePeriod;
use Tiime\EN16931\BusinessTermsGroup\InvoiceNote;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\BusinessTermsGroup\InvoicingPeriod;
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
use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\Invoice;

class BusinessRulesIntegrityConstraintsTest extends TestCase
{
    private Invoice $invoice;

    protected function setUp(): void
    {
        $this->invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller('John Doe', new SellerPostalAddress(CountryAlpha2Code::FRANCE)),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )]
        ))
            ->setBuyerReference("SERVEXEC")
            ->addIncludedNote(
                new InvoiceNote("Lorem Ipsum"),
                new InvoiceNote("Lorem Ipsum"),
            );
    }


    /** @test BR-1 */
    public function an_invoice_shall_have_a_specification_identifier(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-2 */
    public function an_invoice_shall_have_an_invoice_number(): void
    {
        $this->assertInstanceOf(InvoiceIdentifier::class, $this->invoice->getNumber());
    }

    /** @test BR-3 */
    public function an_invoice_shall_have_an_invoice_issue_date(): void
    {
        $this->assertInstanceOf(\DateTimeInterface::class, $this->invoice->getIssueDate());
    }

    /** @test BR-4 */
    public function an_invoice_shall_have_an_invoice_type_code(): void
    {
        $this->assertSame(InvoiceTypeCode::COMMERCIAL_INVOICE, $this->invoice->getTypeCode());
    }

    /** @test BR-5 */
    public function an_invoice_shall_have_an_invoice_currency_code(): void
    {
        $this->assertSame(CurrencyCode::EURO, $this->invoice->getCurrencyCode());
    }

    /** @test BR-6 */
    public function an_invoice_shall_contain_the_seller_name(): void
    {
        $this->assertSame('John Doe', $this->invoice->getSeller()->getName());
    }

    /** @test BR-7 */
    public function an_invoice_shall_contain_the_buyer_name(): void
    {
        $this->assertSame('Richard Roe', $this->invoice->getBuyer()->getName());
    }

    /** @test BR-8 */
    public function an_invoice_shall_contain_the_seller_postal_address(): void
    {
        $this->assertInstanceOf(SellerPostalAddress::class, $this->invoice->getSeller()->getAddress());
    }

    /** @test BR-9 */
    public function the_seller_postal_address_shall_contain_a_seller_country_code(): void
    {
        $this->assertSame(CountryAlpha2Code::FRANCE, $this->invoice->getSeller()->getAddress()->getCountryCode());
    }

    /** @test BR-10 */
    public function an_invoice_shall_contain_the_buyer_postal_address(): void
    {
        $this->assertInstanceOf(BuyerPostalAddress::class, $this->invoice->getBuyer()->getAddress());
    }

    /** @test BR-11 */
    public function the_buyer_postal_address_shall_contain_a_seller_country_code(): void
    {
        $this->assertSame(CountryAlpha2Code::FRANCE, $this->invoice->getBuyer()->getAddress()->getCountryCode());
    }

    /** @test BR-12 */
    public function an_invoice_shall_have_the_sum_of_invoice_line_net_amount(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-13 */
    public function an_invoice_shall_have_the_invoice_total_amount_without_vat(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-14 */
    public function an_invoice_shall_have_the_invoice_total_amount_with_vat(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-15 */
    public function an_invoice_shall_have_the_amount_due_for_payment(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-16 */
    public function an_invoice_shall_have_at_least_one_invoice_line(): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::MINIMUM)),
            new Seller('John Doe', new SellerPostalAddress(CountryAlpha2Code::FRANCE)),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD)],
            [] // without invoice line
        );
    }

    /** @test BR-17 */
    public function the_payee_name_shall_be_provided_in_the_invoice_if_the_payee_is_different_from_the_seller(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-18 */
    public function the_seller_tax_representative_name_shall_be_provided_in_the_invoice_if_the_seller_has_a_seller_tax_representative_party(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-19 */
    public function the_seller_tax_representative_postal_address_shall_be_provided_in_the_invoice_if_the_seller_has_a_seller_tax_representative_party(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-20 */
    public function the_seller_tax_representative_postal_address_shall_contain_a_tax_representative_country_code_if_the_seller_has_a_seller_tax_representative_party(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-21 */
    public function each_invoice_line_shall_have_an_invoice_line_identifier(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-22 */
    public function each_invoice_line_shall_have_an_invoiced_quantity(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-23 */
    public function an_invoice_line_shall_have_an_invoiced_quantity_unit_of_measure_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-24 */
    public function each_invoice_line_shall_have_an_invoice_line_net_amount(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-25 */
    public function each_invoice_line_shall_contain_the_item_name(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-26 */
    public function each_invoice_line_shall_contain_the_item_net_price(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-27 */
    public function the_item_net_price_shall_not_be_negative(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-28 */
    public function the_item_gross_price_shall_not_be_negative(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-29 */
    public function if_both_invoicing_period_start_date_and_invoicing_period_end_date_are_given_then_the_invoicing_period_end_date_shall_be_later_or_equal_to_the_invoicing_period_start_date(): void
    {
        $this->expectException(\Exception::class);

        new InvoicingPeriod(new \DateTimeImmutable('2021-01-02'), new \DateTimeImmutable('2021-01-01'));
    }

    /** @test BR-30 */
    public function if_both_invoice_line_period_start_date_and_invoice_line_period_end_date_are_given_then_the_invoice_line_period_end_date_shall_be_later_or_equal_to_the_invoice_line_period_start_date(): void
    {
        $this->expectException(\Exception::class);

        new InvoiceLinePeriod(new \DateTimeImmutable('2021-01-02'), new \DateTimeImmutable('2021-01-01'));
    }

    /** @test BR-31 */
    public function each_document_level_allowance_shall_have_a_document_level_allowance_amount(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-32 */
    public function each_document_level_allowance_shall_have_a_document_level_allowance_vat_category_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-33 */
    public function each_document_level_allowance_shall_have_a_document_level_allowance_reason_or_a_document_level_allowance_reason_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-36 */
    public function each_document_level_charge_shall_have_a_document_level_charge_amount(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-37 */
    public function each_document_level_charge_shall_have_a_document_level_charge_vat_category_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-38 */
    public function each_document_level_charge_shall_have_a_document_level_charge_reason_or_a_document_level_charge_reason_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-41 */
    public function each_invoice_line_allowance_shall_have_an_invoice_line_allowance_amount(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-42 */
    public function each_invoice_line_allowance_shall_have_an_invoice_line_allowance_reason_or_an_invoice_line_allowance_reason_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-43 */
    public function each_invoice_line_charge_shall_have_an_invoice_line_charge_amount(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-44 */
    public function each_invoice_line_charge_shall_have_an_invoice_line_charge_reason_or_an_invoice_line_charge_reason_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-45 */
    public function each_vat_breakdown_shall_have_a_vat_category_taxable_amount(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-46 */
    public function each_vat_breakdown_shall_have_a_vat_category_tax_amount(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-47 */
    public function each_vat_breakdown_shall_be_defined_through_a_vat_category_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-48 */
    public function each_vat_breakdown_shall_have_a_vat_category_rate_except_if_the_invoice_is_not_subject_to_vat(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-49 */
    public function a_payment_instruction_shall_specify_the_payment_means_type_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-50 */
    public function a_payment_account_identifier_shall_be_present_if_credit_transfert_information_is_provided_in_the_invoice(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-51 */
    public function the_last_4_to_6_digits_of_the_payment_card_primary_account_number_shall_be_present_if_payment_card_information_is_provided_in_the_invoice(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-52 */
    public function each_additional_supporting_document_shall_contain_a_supporting_document_reference(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-53 */
    public function if_the_vat_accounting_currency_code_is_present_then_the_invoice_total_vat_amount_in_accounting_currency_shall_be_provided(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-54 */
    public function each_item_attribute_shall_contain_an_item_attribute_name_and_an_item_attribute_value(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-55 */
    public function each_preceding_invoice_reference_shall_contain_a_preceding_invoice_reference(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-56 */
    public function each_seller_tax_representative_party_shall_have_a_seller_tax_representative_vat_identifier(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-57 */
    public function each_deliver_ti_address_shall_contain_a_deliver_to_country_code(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-61 */
    public function if_the_payment_means_type_code_means_SEPA_credit_transfer_local_credit_transfer_or_non_SEPA_international_credit_transfer_the_payment_account_identifier_shall_be_present(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-62 */
    public function the_seller_electronic_address_shall_have_a_scheme_identifier(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-63 */
    public function the_buyer_electronic_address_shall_have_a_scheme_identifier(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-64 */
    public function the_item_standard_identifier_shall_have_a_scheme_identifier(): void
    {
        $this->markTestSkipped('@todo');
    }

    /** @test BR-65 */
    public function the_item_classification_identifier_shall_have_a_scheme_identifier(): void
    {
        $this->markTestSkipped('@todo');
    }
}
