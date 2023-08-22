<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

/**
 * Mime type codes - Mime codes (BT-125-1)
 * Published by France (31/07/2023)
 */
enum MimeCode: string
{
    case BMP = 'image/bmp';
    case BZIP2 = 'application/x-bzip2';
    case CSV = 'text/csv';
    case MICROSOFT_WORD = 'application/msword';
    case MICROSOFT_WORD_OPENXML = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    case G3FAX = 'image/g3fax';
    case GIF = 'image/gif';
    case GZIP = 'application/x-gzip';
    case HTML = 'text/html';
    case JPEG = 'image/jpeg';
    case OPEN_DOCUMENT_PRESENTATION = 'application/vnd.oasis.opendocument.presentation';
    case OPEN_DOCUMENT_SPREADSHEET = 'application/vnd.oasis.opendocument.spreadsheet';
    case OPEN_DOCUMENT_TEXT = 'application/vnd.oasis.opendocument.text';
    case PKCS7 = 'application/pkcs7-mime';
    case PDF = 'application/pdf';
    case PNG = 'image/png';
    case MICROSOFT_POWERPOINT = 'application/vnd.ms-powerpoint';
    case MICROSOFT_POWERPOINT_OPENXML = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    case RICH_TEXT_FORMAT = 'application/rtf';
    case SVG = 'image/svg+xml';
    case TAR = 'application/x-tar';
    case TIFF = 'image/tiff';
    case TEXT = 'text/plain';
    case XHTML  = 'application/xhtml+xml';
    case MICROSOFT_EXCEL = 'application/vnd.ms-excel';
    case XML = 'application/xml';
    case TEXT_XML = 'text/xml';
    case MICROSOFT_EXCEL_OPENXML = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    case ZIP = 'application/zip';
}
