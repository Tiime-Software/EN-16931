<?php

declare(strict_types=1);

namespace Tiime\EN16931\Codelist;

enum DutyTaxFeeCategoryCodeUNTDID5305 : string
{
        case STANDARD_RATE = 'S';
        case ZERO_RATED_GOODS = 'Z';
        case EXEMPT_FROM_TAX = 'E';
        case VAT_REVERSE_CHARGE = 'AE';
        case VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES = 'K';
        case FREE_EXPORT_ITEM_TAX_NOT_CHARGED = 'G';
        case SERVICE_OUTSIDE_SCOPE_OF_TAX = 'O';
        case CANARY_ISLANDS_GENERAL_INDIRECT_TAX = 'L';
        case TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA = 'M';
}