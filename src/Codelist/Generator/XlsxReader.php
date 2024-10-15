<?php

namespace Tiime\EN16931\Codelist\Generator;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final readonly class XlsxReader
{
    public static function read(
        string $filename,
        string $valueColumn,
        string $nameColumn,
        string $sheetName,
        int $startLine = 2,
    ): XlsxReaderResult {
        $reader = (new Xlsx())
            ->setLoadSheetsOnly($sheetName)
            ->setReadEmptyCells(false)
            ->setIgnoreRowsWithNoCells(true)
        ;
        $spreadsheet = $reader->load($filename);

        $worksheet = $spreadsheet->getSheetByNameOrThrow($sheetName);
        $highestRow = $worksheet->getHighestRow();

        $result = new XlsxReaderResult();

        for ($row = $startLine; $row <= $highestRow; $row++) {
            $nameFromCell = $worksheet->getCell($nameColumn . $row)->getValue();
            assert(is_string($nameFromCell));
            $valueFromCell = $worksheet->getCell($valueColumn . $row)->getValue();
            assert(is_string($valueFromCell));
            $name = trim($nameFromCell);
            $value = trim($valueFromCell);

            $result->add(new XlsxReaderResultItem(
                name: $name,
                value: $value,
            ));
        }

        return $result;
    }
}