<?php

namespace Tiime\EN16931\BusinessTermsGroup;

/**
 * BG-14
 * A group of business terms providing information on the invoice period.
 */
class InvoicingPeriod
{
    /**
     * BT-73
     * The date when the Invoice period starts.
     */
    private ?\DateTimeInterface $startDate;

    /**
     * BT-74
     * The date when the Invoice period ends.
     */
    private ?\DateTimeInterface $endDate;

    public function __construct(?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate)
    {
        if (
            $startDate instanceof \DateTimeInterface
            && $endDate instanceof \DateTimeInterface
            && $startDate > $endDate
        ) {
            throw new \Exception('@todo');
        }

        if (null === $startDate && null === $endDate) {
            throw new \Exception('@todo : BR-CO-19');
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }
}
