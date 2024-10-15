<?php

declare(strict_types=1);

namespace Tiime\EN16931\Codelist;

enum TimeReferencingCodeUNTDID2005 : string
{
        case INVOICE_DOCUMENT_ISSUE_DATE_TIME = '3';
        case DELIVERY_DATE_TIME_ACTUAL = '35';
        case PAID_TO_DATE = '432';
}