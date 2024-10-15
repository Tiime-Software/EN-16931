<?php

namespace Tiime\EN16931\Codelist\Generator;

final readonly class CodelistGenerator
{
    public static function generateCodelists(
        string $fileToRead
    ): void {
        $generator = new Generator();

        $countryList = XlsxReader::read(filename: $fileToRead, valueColumn: 'B', nameColumn: 'A', sheetName: 'Country');
        $currencyList = XlsxReader::read(filename: $fileToRead, valueColumn: 'B', nameColumn: 'A', sheetName: 'Currency');
        $internationalCodeDesignatorList = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'ICD');
        $code1001List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: '1001');
        $code1153List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: '1153');
        $code2005List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'Time', startLine: 3);
        $code2475List = XlsxReader::read(filename: $fileToRead, valueColumn: 'C', nameColumn: 'D', sheetName: 'Time', startLine: 3);
        $code4451List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'Text',);
        $code4461List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'Payment',);
        $code5305List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: '5305',);
        $code5189List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'Allowance',);
        $code7143List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'Item',);
        $code7161List = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'Charge',);
        $mimeList = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'A', sheetName: 'MIME',);
        $electronicAddressSchemeList = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'EAS',);
        $vatExemptionReasonCodeList = XlsxReader::read(filename: $fileToRead, valueColumn: 'A', nameColumn: 'B', sheetName: 'VATEX',);
        $unitOfMeasure = XlsxReader::read(filename: $fileToRead, valueColumn: 'B', nameColumn: 'C', sheetName: 'Unit',);


        // @todo Greece : EL for France Spec
        $generator->generateCodelist(className: 'CountryAlpha2Code', cases: $countryList);
        $generator->generateCodelist(className: 'CurrencyCodeISO4217', cases: $currencyList);
        $generator->generateCodelist(className: 'InternationalCodeDesignator', cases: $internationalCodeDesignatorList);
        $generator->generateCodelist(className: 'InvoiceTypeCodeUNTDID1001', cases: $code1001List);
        $generator->generateCodelist(className: 'ReferenceQualifierCodeUNTDID1153', cases: $code1153List);
        $generator->generateCodelist(className: 'TimeReferencingCodeUNTDID2005', cases: $code2005List);
        $generator->generateCodelist(className: 'TimeReferencingCodeUNTDID2475', cases: $code2475List);
        $generator->generateCodelist(className: 'TextSubjectCodeUNTDID4451', cases: $code4451List);
        $generator->generateCodelist(className: 'PaymentMeansCodeUNTDID4461', cases: $code4461List);
        $generator->generateCodelist(className: 'DutyTaxFeeCategoryCodeUNTDID5305', cases: $code5305List);
        $generator->generateCodelist(className: 'AllowanceReasonCodeUNTDID5189', cases: $code5189List);
        $generator->generateCodelist(className: 'ItemTypeCodeUNTDID7143', cases: $code7143List);
        $generator->generateCodelist(className: 'ChargeReasonCodeUNTDID7161', cases: $code7161List);
        $generator->generateCodelist(className: 'MimeCode', cases: $mimeList);
        $generator->generateCodelist(className: 'ElectronicAddressSchemeCode', cases: $electronicAddressSchemeList);
        $generator->generateCodelist(className: 'VatExemptionReasonCodeList', cases: $vatExemptionReasonCodeList);
        $generator->generateCodelist(className: 'UnitOfMeasureCode', cases: $unitOfMeasure);
    }
}
