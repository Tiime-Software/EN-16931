<?php

namespace Tests\Tiime\EN16931;

use PHPUnit\Framework\TestCase;

class BusinessRulesVatRulesTest extends TestCase
{
    /** @test BR-S-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_standard_rated_shall_contain_in_the_vat_breakdown_at_least_one_vat_category_code_equal_with_standard_rated(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo

    /** @test BR-Z-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_zero_rated_shall_contain_in_the_vat_breakdown_exactly_one_vat_category_code_with_zero_rated(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo

    /** @test BR-E-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_exempt_from_vat_shall_contain_exactly_one_vat_breakdown_with_vat_category_code_equal_to_exempt_from_vat(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo

    /** @test BR-AE-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_reverse_charge_shall_contain_in_the_vat_breakdown_exactly_one_vat_category_code_equal_with_vat_reverse_charge(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo

    /** @test BR-IC-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_intra_community_supply_shall_contain_in_the_vat_breakdown_exactly_one_vat_category_code_equal_with_intra_community_supply(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo

    /** @test BR-G-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_export_outside_EU_shall_contain_in_the_vat_breakdown_exactly_one_vat_category_code_equal_with_export_outside_EU(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo

    /** @test BR-O-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_no_subject_to_vat_shall_contain_in_the_vat_breakdown_exactly_one_vat_category_code_equal_with_no_subject_to_vat(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo

    /** @test BR-IG-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_IGIC_shall_contain_in_the_vat_breakdown_at_least_one_vat_category_code_equal_with_IGIC(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo

    /** @test BR-IP-1 */
    public function an_invoice_that_contain_an_invoice_line_a_document_level_allowance_or_a_document_level_charge_where_the_vat_category_code_is_IPSI_shall_contain_in_the_vat_breakdown_at_least_one_vat_category_code_equal_with_IPSI(): void
    {
        $this->markTestSkipped('@todo');
    }

    // @todo
}
