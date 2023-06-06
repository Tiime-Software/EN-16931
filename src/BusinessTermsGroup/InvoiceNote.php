<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\InvoiceNoteCode;

/**
 * BG-1
 * A group of business terms providing textual notes that are relevant for the invoice,
 * together with an indication of the note subject.
 */
class InvoiceNote
{
    /**
     * BT-21
     * The subject of the textual note in BT-22.
     */
    private ?InvoiceNoteCode $subjectCode;

    /**
     * BT-22
     * A textual note that gives unstructured information that is relevant to the Invoice as a whole.
     */
    private string $note;

    public function __construct(string $note)
    {
        $this->note = $note;
        $this->subjectCode = null;
    }

    public function getSubjectCode(): ?InvoiceNoteCode
    {
        return $this->subjectCode;
    }

    public function setSubjectCode(?InvoiceNoteCode $subjectCode): self
    {
        $this->subjectCode = $subjectCode;

        return $this;
    }

    public function getNote(): string
    {
        return $this->note;
    }
}
