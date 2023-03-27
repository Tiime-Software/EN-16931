<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

enum InvoiceTypeCode: string
{
    case DEBIT_NOTE_RELATED_TO_GOODS_OR_SERVICES = '80';
    case CREDIT_NOTE_RELATED_TO_GOODS_OR_SERVICES = '81';
    case METERED_SERVICES_INVOICE = '82';
    case CREDIT_NOTE_RELATED_TO_FINANCIAL_ADJUSTMENTS = '83';
    case DEBIT_NOTE_RELATED_TO_FINANCIAL_ADJUSTMENTS = '84';
    case INVOICING_DATA_SHEET = '130';
    case DIRECT_PAYMENT_VALUATION = '202';
    case PROVISIONAL_PAYMENT_VALUATION = '203';
    case PAYMENT_VALUATION = '204';
    case INTERIM_APPLICATION_FOR_PAYMENT = '211';
    case SELF_BILLED_CREDIT_NOTE = '261';
    case CONSOLIDATED_CREDIT_NOTE_GOODS_AND_SERVICES = '262';
    case PRICE_VARIATION_INVOICE = '295';
    case CREDIT_NOTE_FOR_PRICE_VARIATION = '296';
    case DELCREDERE_CREDIT_NOTE = '308';
    case PROFORMA_INVOICE = '325';
    case PARTIAL_INVOICE = '326';
    case COMMERCIAL_INVOICE = '380';
    case CREDIT_NOTE = '381';
    case DEBIT_NOTE = '383';
    case CORRECTED_INVOICE = '384';
    case CONSOLIDATED_INVOICE = '385';
    case PREPAYMENT_INVOICE = '386';
    case HIRE_INVOICE = '387';
    case TAX_INVOICE = '388';
    case SELF_BILLED_INVOICE = '389';
    case DELCREDERE_INVOICE = '390';
    case FACTORED_INVOICE = '393';
    case LEASE_INVOICE = '394';
    case CONSIGNMENT_INVOICE = '395';
    case FACTORED_CREDIT_NOTE = '396';
    case OCR_PAYMENT_CREDIT_NOTE = '420';
    case DEBIT_ADVICE = '456';
    case REVERSAL_OF_DEBIT = '457';
    case REVERSAL_OF_CREDIT = '458';
    case SELF_BILLED_DEBIT_NOTE = '527';
    case FORWARDER_CREDIT_NOTE = '532';
    case INSURER_INVOICE = '575';
    case FORWARDER_INVOICE = '623';
    case PORT_CHARGES_DOCUMENTS = '633';
    case INVOICE_INFORMATION_FOR_ACCOUNTING_PURPOSES = '751';
    case FREIGHT_INVOICE = '780';
    case CUSTOMS_INVOICE = '935';
}
