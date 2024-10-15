<?php

declare(strict_types=1);

namespace Tiime\EN16931\Codelist;

enum MimeCode : string
{
        case APPLICATION_PDF = 'application/pdf';
        case IMAGE_PNG = 'image/png';
        case IMAGE_JPEG = 'image/jpeg';
        case TEXT_CSV = 'text/csv';
        case APPLICATION_VNDOPENXMLFORMATS_OFFICEDOCUMENTSPREADSHEETMLSHEET = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        case APPLICATION_VNDOASISOPENDOCUMENTSPREADSHEET = 'application/vnd.oasis.opendocument.spreadsheet';
}