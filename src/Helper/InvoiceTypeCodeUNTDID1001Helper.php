<?php

namespace Tiime\EN16931\Helper;

use Tiime\EN16931\Codelist\InvoiceTypeCodeUNTDID1001;

class InvoiceTypeCodeUNTDID1001Helper
{
    public static function isInvoice(InvoiceTypeCodeUNTDID1001 $code): bool
    {
        if (
            \in_array($code, [
                InvoiceTypeCodeUNTDID1001::CREDIT_NOTE_RELATED_TO_GOODS_OR_SERVICES,
                InvoiceTypeCodeUNTDID1001::CREDIT_NOTE_RELATED_TO_FINANCIAL_ADJUSTMENTS,
                InvoiceTypeCodeUNTDID1001::SELF_BILLED_CREDIT_NOTE,
                InvoiceTypeCodeUNTDID1001::CONSOLIDATED_CREDIT_NOTE_GOODS_AND_SERVICES,
                InvoiceTypeCodeUNTDID1001::CREDIT_NOTE_FOR_PRICE_VARIATION,
                InvoiceTypeCodeUNTDID1001::DELCREDERE_CREDIT_NOTE,
                InvoiceTypeCodeUNTDID1001::CREDIT_NOTE,
                InvoiceTypeCodeUNTDID1001::FACTORED_CREDIT_NOTE,
                InvoiceTypeCodeUNTDID1001::OPTICAL_CHARACTER_READING_OCR_PAYMENT_CREDIT_NOTE,
                InvoiceTypeCodeUNTDID1001::REVERSAL_OF_CREDIT,
                InvoiceTypeCodeUNTDID1001::FORWARDERS_CREDIT_NOTE,
            ])
        ) {
            return false;
        }

        return true;
    }
}
