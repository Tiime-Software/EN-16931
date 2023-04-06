<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Reference\PrecedingInvoiceReference;

/**
 * BG-3
 * A group of business terms providing information on one or more preceding Invoices.
 */
class PrecedingInvoice
{
    /**
     * BT-25
     * The identification of an Invoice that was previously sent by the Seller.
     */
    private PrecedingInvoiceReference $reference;

    /**
     * BT-26
     * The date when the Preceding Invoice was issued.
     */
    private ?\DateTimeInterface $issueDate;

    public function __construct(PrecedingInvoiceReference $reference)
    {
        $this->reference = $reference;
        $this->issueDate = null;
    }

    public function getReference(): PrecedingInvoiceReference
    {
        return $this->reference;
    }

    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->issueDate;
    }

    public function setIssueDate(?\DateTimeInterface $issueDate): self
    {
        $this->issueDate = $issueDate;

        return $this;
    }
}
