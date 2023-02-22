<?php

namespace Tiime\EN16931\DataType;

enum MimeCode: string
{
    case PDF = "application/pdf";
    case PNG = "image/png";
    case JPEG = "image/jpeg";
    case CSV = "text/csv";
    case EXCEL_OPENXML = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
    case OPENDOCUMENT_SPREADSHEET = "application/vnd.oasis.opendocument.spreadsheet";
}
