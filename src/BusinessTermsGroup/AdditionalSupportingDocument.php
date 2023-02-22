<?php

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\BinaryObject;
use Tiime\EN16931\DataType\Reference\SupportingDocumentReference;

/**
 * BG-24
 * A group of business terms providing information about additional supporting documents substantiating
 * the claims made in the Invoice.
 */
class AdditionalSupportingDocument
{
    /**
     * BT-122
     * An identifier of the supporting document.
     */
    private SupportingDocumentReference $reference;

    /**
     * BT-123
     * A description of the supporting document.
     */
    private ?string $description;

    /**
     * BT-124
     * The URL (Uniform Resource Locator) that identifies where the external document is located.
     */
    private ?string $externalDocumentLocation;

    /**
     * BT-125
     * An attached document embedded as binary object or sent together with the invoice.
     */
    private ?BinaryObject $attachedDocument;

    public function __construct(SupportingDocumentReference $reference)
    {
        $this->reference = $reference;
        $this->description = null;
        $this->externalDocumentLocation = null;
        $this->attachedDocument = null;
    }

    public function getReference(): SupportingDocumentReference
    {
        return $this->reference;
    }

    public function setReference(SupportingDocumentReference $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExternalDocumentLocation(): ?string
    {
        return $this->externalDocumentLocation;
    }

    public function setExternalDocumentLocation(?string $externalDocumentLocation): self
    {
        $this->externalDocumentLocation = $externalDocumentLocation;

        return $this;
    }

    public function getAttachedDocument(): ?BinaryObject
    {
        return $this->attachedDocument;
    }

    public function setAttachedDocument(?BinaryObject $attachedDocument): self
    {
        $this->attachedDocument = $attachedDocument;

        return $this;
    }
}
