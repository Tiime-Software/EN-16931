<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

/**
 * BG-26
 * A group of business terms providing information about the period relevant for the Invoice line.
 */
class InvoiceLinePeriod
{
    /**
     * BT-134
     * The date when the Invoice period for this Invoice line starts.
     */
    private ?\DateTimeInterface $startDate;

    /**
     * BT-135
     * The date when the Invoice period for this Invoice line ends.
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
            throw new \Exception('@todo : BR-CO-20');
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
