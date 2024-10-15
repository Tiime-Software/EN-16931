<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

/**
 * @deprecated
 */
enum InvoiceNoteCode: string
{
    /** Plain language description of the nature of a goods item sufficient to identify it for customs, statistical or transport purposes. */
    case GOODS_ITEM_DESCRIPTION = 'AAA';
    /** Free form description of the conditions of payment between the parties to a transaction. */
    case PAYMENT_TERM = 'AAB';
    /** Additional information concerning dangerous substances and/or article in a consignment. */
    case DANGEROUS_GOODS_ADDITIONAL_INFORMATION = 'AAC';
    /** Proper shipping name, supplemented as necessary with the correct technical name, by which a dangerous substance or article may be correctly identified, or which is sufficiently informative to permit identification by reference to generally available literature.*/
    case DANGEROUS_GOODS_TECHNICAL_NAME = 'AAD';
    /** The content of an acknowledgement. */
    case ACKNOWLEDGEMENT_DESCRIPTION = 'AAE';
    /** Specific details applying to rates. */
    case RATE_ADDITIONAL_INFORMATION = 'AAF';
    /** Indicates that the segment contains instructions to be passed on to the identified party. */
    case PARTY_INSTRUCTIONS = 'AAG';
    /** The text contains general information. */
    case GENERAL_INFORMATION = 'AAI';
    /** Additional conditions specific to this order or project. */
    case ADDITIONAL_CONDITIONS_OF_SALE_OR_PURCHASE = 'AAJ';
    /** Information on the price conditions that are expected or given. */
    case PRICE_CONDITIONS = 'AAK';
    /** Expression of a number in characters as length of ten meters. */
    case GOODS_DIMENSIONS_IN_CHARACTERS = 'AAL';
    /** Technical or commercial reasons why a piece of equipment may not be re-used after the current transport terminates. */
    case EQUIPMENT_RE_USAGE_RESTRICTIONS = 'AAM';
    /** Restrictions in handling depending on the technical characteristics of the piece of equipment or on the nature of the goods. */
    case HANDLING_RESTRICTION = 'AAN';
    /** Error described by a free text. */
    case ERROR_DESCRIPTION_FREE_TEXT = 'AAO';
    /** Free text of the response to a communication. */
    case RESPONSE_FREE_TEXT = 'AAP';
    /** A description of the contents of a package. */
    case PACKAGE_CONTENT_DESCRIPTION = 'AAQ';
    /** Free text of the non Incoterms terms of delivery. For Incoterms, use: 4053. */
    case TERMS_OF_DELIVERY = 'AAR';
    /** The remarks printed or to be printed on a bill of lading. */
    case BILL_OF_LADING_REMARKS = 'AAS';
    /** Free text information on an IATA Air Waybill to indicate means by which account is to be settled. */
    case MODE_OF_SETTLEMENT_INFORMATION = 'AAT';
    /** Information pertaining to the invoice covering the consignment. */
    case CONSIGNMENT_INVOICE_INFORMATION = 'AAU';
    /** Information pertaining to the invoice covering clearance of the cargo. */
    case CLEARANCE_INVOICE_INFORMATION = 'AAV';
    /** Information pertaining to the letter of credit. */
    case LETTER_OF_CREDIT_INFORMATION = 'AAW';
    /** Information pertaining to a license. */
    case LICENSE_INFORMATION = 'AAX';
    /** The text contains certification statements. */
    case CERTIFICATION_STATEMENTS = 'AAY';
    /** The text contains additional export information. */
    case ADDITIONAL_EXPORT_INFORMATION = 'AAZ';
    /** Description of parameters relating to a tariff. */
    case MEDICAL_HISTORY = 'ABB';
    /** Historical details of a patients medical events. */
    case TARIFF_STATEMENTS = 'ABA';
    /** Additional information regarding terms and conditions which apply to the transaction. */
    case CONDITIONS_OF_SALE_OR_PURCHASE = 'ABC';
    /** Textual representation of the type of contract. */
    case CONTRACT_DOCUMENT_TYPE = 'ABD';
    /** Additional terms and/or conditions to the documentary credit. */
    case ADDITIONAL_TERMS_AND_CONDITIONS_DOCUMENTARY_CREDIT = 'ABE';
    /** credit Instruction or information about a standby documentary credit. */
    case INSTRUCTIONS_OR_INFORMATION_ABOUT_STANDBY_DOCUMENTARY = 'ABF';
    /** Instructions or information about partial shipment(s). */
    case INSTRUCTIONS_OR_INFORMATION_ABOUT_TRANSHIPMENT = 'ABH';
    /** Instructions or information about transhipment(s). */
    case INSTRUCTIONS_OR_INFORMATION_ABOUT_PARTIAL_SHIPMENT = 'ABG';
    /** Additional handling instructions for a documentary credit. */
    case DOMESTIC_ROUTING_INFORMATION = 'ABJ';
    /** Information regarding the domestic routing. */
    case ADDITIONAL_HANDLING_INSTRUCTIONS_DOCUMENTARY_CREDIT = 'ABI';
    /** Equipment types are coded by category for financial purposes. */
    case GOVERNMENT_INFORMATION = 'ABL';
    /** Information pertaining to government. */
    case CHARGEABLE_CATEGORY_OF_EQUIPMENT = 'ABK';
    /** The text contains onward routing information. */
    case ONWARD_ROUTING_INFORMATION = 'ABM';
    /** The text contains information related to accounting. */
    case ACCOUNTING_INFORMATION = 'ABN';
    /** Free text or coded information to indicate a specific discrepancy. */
    case DISCREPANCY_INFORMATION = 'ABO';
    /** Documentary credit confirmation instructions. */
    case METHOD_OF_ISSUANCE = 'ABQ';
    /** Method of issuance of documentary credit. */
    case CONFIRMATION_INSTRUCTIONS = 'ABP';
    /** Delivery instructions for documents required under a documentary credit. */
    case ADDITIONAL_CONDITIONS = 'ABS';
    /** Additional conditions to the issuance of a documentary credit. */
    case DOCUMENTS_DELIVERY_INSTRUCTIONS = 'ABR';
    /** Additional amounts information/instruction. */
    case DEFERRED_PAYMENT_TERMED_ADDITIONAL = 'ABU';
    /** Additional terms concerning deferred payment. */
    case INFORMATION_OR_INSTRUCTIONS_ABOUT_ADDITIONAL_AMOUNTS_COVERED = 'ABT';
    /** Additional terms concerning acceptance. */
    case ACCEPTANCE_TERMS_ADDITIONAL = 'ABV';
    /** Additional terms concerning negotiation. */
    case NEGOTIATION_TERMS_ADDITIONAL = 'ABW';
    /** Document name and documentary requirements. */
    case INSTRUCTIONS_OR_INFORMATION_ABOUT_REVOLVING_DOCUMENTARY_CREDIT = 'ABZ';
    /** Instructions/information about a revolving documentary credit. */
    case DOCUMENT_NAME_AND_DOCUMENTARY_REQUIREMENTS = 'ABX';
    /** Specification of the documentary requirements. */
    case ADDITIONAL_INFORMATION = 'ACB';
    /** The text contains additional information. */
    case DOCUMENTARY_REQUIREMENTS = 'ACA';
    /** Assignment based on an agreement between seller and factor. */
    case REASON = 'ACD';
    /** Reason for a request or response. */
    case FACTOR_ASSIGNMENT_CLAUSE = 'ACC';
    /** A notice, usually from buyer to seller, that something was found wrong with goods delivered or the services rendered, or with the related invoice. */
    case DISPUTE = 'ACE';
    /** The text refers to information about an additional attribute not otherwise specified. */
    case ADDITIONAL_ATTRIBUTE_INFORMATION = 'ACF';
    /** A declaration on the reason of the absence. */
    case ABSENCE_DECLARATION = 'ACG';
    /** A statement on the way a specific variable or set of variables has been aggregated. */
    case AGGREGATION_STATEMENT = 'ACH';
    /** A statement on the compilation status of an array or other set of figures or calculations. */
    case COMPILATION_STATEMENT = 'ACI';
    /** An exception to the agreed definition of a term, concept, formula or other object. */
    case DEFINITIONAL_EXCEPTION = 'ACJ';
    /** A statement on the privacy or confidential nature of an object. */
    case PRIVACY_STATEMENT = 'ACK';
    /** A statement on the quality of an object. */
    case QUALITY_STATEMENT = 'ACL';
    /** The description of a statistical object such as a value list, concept, or structure definition. */
    case STATISTICAL_DESCRIPTION = 'ACM';
    /** The definition of a statistical object such as a value list, concept, or structure definition. */
    case STATISTICAL_DEFINITION = 'ACN';
    /** The name of a statistical object such as a value list, concept or structure definition. */
    case STATISTICAL_NAME = 'ACO';
    /** The title of a statistical object such as a value list, concept, or structure definition. */
    case STATISTICAL_TITLE = 'ACP';
    /** Information relating to differences between the actual transport dimensions and the normally applicable dimensions. */
    case OFF_DIMENSION_INFORMATION = 'ACQ';
    /** Information relating to unexpected stops during a conveyance. */
    case PRINCIPLES = 'ACS';
    /** Text subject is principles section of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case UNEXPECTED_STOPS_INFORMATION = 'ACR';
    /** Text subject is terms and definition section of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case TERMS_AND_DEFINITION = 'ACT';
    /** Text subject is segment name. */
    case SIMPLE_DATA_ELEMENT_NAME = 'ACV';
    /** Text subject is name of simple data element. */
    case SEGMENT_NAME = 'ACU';
    /** Text subject is scope section of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case SCOPE = 'ACW';
    /** Text subject is name of message type. */
    case MESSAGE_TYPE_NAME = 'ACX';
    /** Text subject is introduction section of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case INTRODUCTION = 'ACY';
    /** Text subject is glossary section of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case GLOSSARY = 'ACZ';
    /** Text subject is functional definition section of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case FUNCTIONAL_DEFINITION = 'ADA';
    /** Text subject is examples as given in the example(s) section of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case EXAMPLES = 'ADB';
    /** Text subject is cover page of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case COVER_PAGE = 'ADC';
    /** Denotes that the associated text is a dependency (syntax) note. */
    case DEPENDENCY_SYNTAX_NOTES = 'ADD';
    /** Text subject is name of code value. */
    case CODE_VALUE_NAME = 'ADE';
    /** Text subject is name of code list. */
    case CODE_LIST_NAME = 'ADF';
    /** Text subject is an explanation of the intended usage of a segment or segment group. */
    case CLARIFICATION_OF_USAGE = 'ADG';
    /** Text subject is name of composite data element. */
    case COMPOSITE_DATA_ELEMENT_NAME = 'ADH';
    /** Text subject is field of application of the UN/EDIFACT rules for presentation of standardized message and directories documentation. */
    case FIELD_OF_APPLICATION = 'ADI';
    /** Information describing the type of assets and liabilities. */
    case TYPE_OF_ASSETS_AND_LIABILITIES = 'ADJ';
    /** The text contains information about a promotion. */
    case METER_CONDITION = 'ADL';
    /** Description of the condition of a meter. */
    case PROMOTION_INFORMATION = 'ADK';
    /** Information related to a particular reading of a meter. */
    case METER_READING_INFORMATION = 'ADM';
    /** Information describing the type of the reason of transaction. */
    case TYPE_OF_TRANSACTION_REASON = 'ADN';
    /** Type of survey question. */
    case CARRIER_AGENT_COUNTER_INFORMATION = 'ADP';
    /** Information for use at the counter of the carrier's agent. */
    case TYPE_OF_SURVEY_QUESTION = 'ADO';
    /** Description or code for the operation to be executed on the equipment. */
    case DESCRIPTION_OF_WORK_ITEM_ON_EQUIPMENT = 'ADQ';
    /** Text subject is message definition. */
    case MESSAGE_DEFINITION = 'ADR';
    /** Information pertaining to a booked item. */
    case SOURCE_OF_DOCUMENT = 'ADT';
    /** Text subject is source of document. */
    case BOOKED_ITEM_INFORMATION = 'ADS';
    /** Text subject is note. */
    case NOTE = 'ADU';
    /** Text subject is fixed part of segment clarification text. */
    case CHARACTERISTICS_OF_GOODS = 'ADW';
    /** Description of the characteristic of goods in addition to the description of the goods. */
    case FIXED_PART_OF_SEGMENT_CLARIFICATION_TEXT = 'ADV';
    /** Special discharge instructions concerning the goods. */
    case ADDITIONAL_DISCHARGE_INSTRUCTIONS = 'ADX';
    /** Instructions regarding the stripping of container(s). */
    case CSC_CONTAINER_SAFETY_CONVENTION_PLATE_INFORMATION = 'ADZ';
    /** Information on the CSC (Container Safety Convention) plate that is attached to the container. */
    case CONTAINER_STRIPPING_INSTRUCTIONS = 'ADY';
    /** Additional remarks concerning the cargo. */
    case CARGO_REMARKS = 'AEA';
    /** Instruction regarding the temperature control of the cargo. */
    case TEXT_REFERS_TO_EXPECTED_DATA = 'AEC';
    /** Remarks refer to data that was expected. */
    case TEMPERATURE_CONTROL_INSTRUCTIONS = 'AEB';
    /** Remarks refer to data that was received. */
    case TEXT_REFERS_TO_RECEIVED_DATA = 'AED';
    /** Text subject is section clarification text. */
    case SECTION_CLARIFICATION_TEXT = 'AEE';
    /** Information given to the beneficiary. */
    case INFORMATION_TO_THE_APPLICANT = 'AEG';
    /** Information given to the applicant. */
    case INFORMATION_TO_THE_BENEFICIARY = 'AEF';
    /** Instructions made to the beneficiary. */
    case INSTRUCTIONS_TO_THE_BENEFICIARY = 'AEH';
    /** Instructions given to the applicant. */
    case CONTROLLED_ATMOSPHERE = 'AEJ';
    /** Information about the controlled atmosphere. */
    case TAKE_OFF_ANNOTATION = 'AEK';
    /** Additional information in plain text to support a take off annotation. Taking off is the process of assessing the quantity work from extracting the measurement from caseruction documentation. */
    case INSTRUCTIONS_TO_THE_APPLICANT = 'AEI';
    /** Additional information in plain language to support a price variation. */
    case PRICE_VARIATION_NARRATIVE = 'AEL';
    /** Documentary credit amendment instructions. */
    case STANDARD_METHOD_NARRATIVE = 'AEN';
    /** Additional information in plain language to support a standard method. */
    case DOCUMENTARY_CREDIT_AMENDMENT_INSTRUCTIONS = 'AEM';
    /** Additional information in plain language to support the project. */
    case PROJECT_NARRATIVE = 'AEO';
    /** Additional information related to radioactive goods. */
    case RADIOACTIVE_GOODS_ADDITIONAL_INFORMATION = 'AEP';
    /** Information given from one bank to another. */
    case BANK_TO_BANK_INFORMATION = 'AEQ';
    /** Instructions given for reimbursement purposes. */
    case REASON_FOR_AMENDING_A_MESSAGE = 'AES';
    /** Identification of the reason for amending a message. */
    case REIMBURSEMENT_INSTRUCTIONS = 'AER';
    /** negotiating bank Instructions to the paying and/or accepting and/or negotiating bank. */
    case INSTRUCTIONS_TO_THE_PAYING_AND_OR_ACCEPTING_AND_OR_NEGOTIATING_BANK = 'AET';
    /** Instructions given about the interest. */
    case AGENT_COMMISSION = 'AEV';
    /** Instructions about agent commission. */
    case REMITTING_BANK_INSTRUCTIONS = 'AEW';
    /** Instructions to the remitting bank. */
    case INTEREST_INSTRUCTIONS = 'AEU';
    /** Instructions to the bank, other than the remitting bank, involved in processing the collection. */
    case INSTRUCTIONS_TO_THE_COLLECTING_BANK = 'AEX';
    /** Instructions about the collection amount. */
    case INTERNAL_AUDITING_INFORMATION = 'AEZ';
    /** Text relating to internal auditing information. */
    case COLLECTION_AMOUNT_INSTRUCTIONS = 'AEY';
    /** Denotes that the associated text is a caseraint. */
    case CASERAINT = 'AFA';
    /** Denotes that the associated text is a comment. */
    case SEMANTIC_NOTE = 'AFC';
    /** Denotes that the associated text is a semantic note. */
    case COMMENT = 'AFB';
    /** Denotes that the associated text is an item of help text. */
    case HELP_TEXT = 'AFD';
    /** Denotes that the associated text is a legend. */
    case LEGEND = 'AFE';
    /** A description of the structure of a batch code. */
    case BATCH_CODE_STRUCTURE = 'AFF';
    /** A general description of the application of a product. */
    case PRODUCT_APPLICATION = 'AFG';
}
