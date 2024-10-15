<?php

declare(strict_types=1);

namespace Tiime\EN16931\Codelist;

enum VatExemptionReasonCodeList : string
{
        case EXEMPT_BASED_ON_ARTICLE_79_POINT_C_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-79-C';
        case EXEMPT_BASED_ON_ARTICLE_132_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_A_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1A';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_B_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1B';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_C_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1C';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_D_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1D';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_E_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1E';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_F_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1F';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_G_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1G';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_H_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1H';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_I_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1I';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_J_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1J';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_K_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1K';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_L_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1L';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_M_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1M';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_N_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1N';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_O_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1O';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_P_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1P';
        case EXEMPT_BASED_ON_ARTICLE_132_SECTION_1_Q_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-132-1Q';
        case EXEMPT_BASED_ON_ARTICLE_143_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_A_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1A';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_B_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1B';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_C_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1C';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_D_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1D';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_E_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1E';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_F_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1F';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_FA_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1FA';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_G_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1G';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_H_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1H';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_I_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1I';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_J_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1J';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_K_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1K';
        case EXEMPT_BASED_ON_ARTICLE_143_SECTION_1_L_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-143-1L';
        case EXEMPT_BASED_ON_ARTICLE_144_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-144';
        case EXEMPT_BASED_ON_ARTICLE_146_SECTION_1_E_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-146-1E';
        case EXEMPT_BASED_ON_ARTICLE_148_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-148';
        case EXEMPT_BASED_ON_ARTICLE_148_SECTION_A_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-148-A';
        case EXEMPT_BASED_ON_ARTICLE_148_SECTION_B_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-148-B';
        case EXEMPT_BASED_ON_ARTICLE_148_SECTION_C_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-148-C';
        case EXEMPT_BASED_ON_ARTICLE_148_SECTION_D_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-148-D';
        case EXEMPT_BASED_ON_ARTICLE_148_SECTION_E_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-148-E';
        case EXEMPT_BASED_ON_ARTICLE_148_SECTION_F_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-148-F';
        case EXEMPT_BASED_ON_ARTICLE_148_SECTION_G_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-148-G';
        case EXEMPT_BASED_ON_ARTICLE_151_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-151';
        case EXEMPT_BASED_ON_ARTICLE_151_SECTION_1_A_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-151-1A';
        case EXEMPT_BASED_ON_ARTICLE_151_SECTION_1_AA_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-151-1AA';
        case EXEMPT_BASED_ON_ARTICLE_151_SECTION_1_B_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-151-1B';
        case EXEMPT_BASED_ON_ARTICLE_151_SECTION_1_C_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-151-1C';
        case EXEMPT_BASED_ON_ARTICLE_151_SECTION_1_D_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-151-1D';
        case EXEMPT_BASED_ON_ARTICLE_151_SECTION_1_E_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-151-1E';
        case EXEMPT_BASED_ON_ARTICLE_159_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-159';
        case EXEMPT_BASED_ON_ARTICLE_309_OF_COUNCIL_DIRECTIVE_2006_112_EC = 'VATEX-EU-309';
        case REVERSE_CHARGE = 'VATEX-EU-AE';
        case TRAVEL_AGENTS_VAT_SCHEME = 'VATEX-EU-D';
        case SECOND_HAND_GOODS_VAT_SCHEME = 'VATEX-EU-F';
        case EXPORT_OUTSIDE_THE_EU = 'VATEX-EU-G';
        case WORKS_OF_ART_VAT_SCHEME = 'VATEX-EU-I';
        case INTRA_COMMUNITY_SUPPLY = 'VATEX-EU-IC';
        case COLLECTORS_ITEMS_AND_ANTIQUES_VAT_SCHEME = 'VATEX-EU-J';
        case NOT_SUBJECT_TO_VAT = 'VATEX-EU-O';
        case FRANCE_DOMESTIC_VAT_FRANCHISE_IN_BASE = 'VATEX-FR-FRANCHISE';
        case FRANCE_DOMESTIC_CREDIT_NOTES_WITHOUT_VAT_DUE_TO_SUPPLIER_FORFEIT_OF_VAT_FOR_DISCOUNT = 'VATEX-FR-CNWVAT';
}