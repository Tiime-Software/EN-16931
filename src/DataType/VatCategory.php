<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

/**
 * UNTDID 5305 - Duty or tax or fee category (BT-151 & BG-20 & BT-95 & BT-102 & BT-118)
 * Published by France (31/07/2023)
 */
enum VatCategory: string
{
    case VAT_REVERSE_CHARGE = 'AE';
    case EXEMPT_FROM_TAX = 'E';
    case FREE_EXPORT_ITEM_TAX_NOT_CHARGED = 'G';
    case VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES = 'K';
    case CANARY_ISLANDS_GENERAL_INDIRECT_TAX = 'L';
    case TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA = 'M';
    case SERVICE_OUTSIDE_SCOPE_OF_TAX = 'O';
    case STANDARD_RATE = 'S';
    case ZERO_RATED_GOODS = 'Z';
}
