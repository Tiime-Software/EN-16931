<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Identifier\LocationIdentifier;

/**
 * BG-13
 * A group of business terms providing information about where and when the goods and services invoiced are delivered.
 */
class DeliveryInformation
{
    /**
     * BT-70
     * The name of the party to which the goods and services are delivered.
     */
    private ?string $deliverToPartyName;

    /**
     * BT-71
     * An identifier for the location at which the goods and services are delivered.
     */
    private ?LocationIdentifier $locationIdentifier;

    /**
     * BT-72
     * The date on which the supply of goods or services was made or completed.
     */
    private ?\DateTimeInterface $actualDeliveryDate;

    /**
     * BG-14
     * A group of business terms providing information on the invoice period.
     */
    private ?InvoicingPeriod $invoicingPeriod;

    /**
     * BG-15
     * A group of business terms providing information about the address to which
     * goods and services invoiced were or are delivered.
     */
    private ?DeliverToAddress $deliverToAddress;

    public function __construct() {
        $this->deliverToPartyName = null;
        $this->locationIdentifier = null;
        $this->actualDeliveryDate = null;
        $this->invoicingPeriod = null;
        $this->deliverToAddress = null;
    }

    public function getDeliverToPartyName(): ?string
    {
        return $this->deliverToPartyName;
    }

    public function setDeliverToPartyName(?string $deliverToPartyName): self
    {
        $this->deliverToPartyName = $deliverToPartyName;

        return $this;
    }

    public function getLocationIdentifier(): ?LocationIdentifier
    {
        return $this->locationIdentifier;
    }

    public function setLocationIdentifier(?LocationIdentifier $locationIdentifier): self
    {
        $this->locationIdentifier = $locationIdentifier;

        return $this;
    }

    public function getActualDeliveryDate(): ?\DateTimeInterface
    {
        return $this->actualDeliveryDate;
    }

    public function setActualDeliveryDate(?\DateTimeInterface $actualDeliveryDate): self
    {
        $this->actualDeliveryDate = $actualDeliveryDate;

        return $this;
    }

    public function getInvoicingPeriod(): ?InvoicingPeriod
    {
        return $this->invoicingPeriod;
    }

    public function setInvoicingPeriod(?InvoicingPeriod $invoicingPeriod): self
    {
        $this->invoicingPeriod = $invoicingPeriod;

        return $this;
    }

    public function getDeliverToAddress(): ?DeliverToAddress
    {
        return $this->deliverToAddress;
    }

    public function setDeliverToAddress(?DeliverToAddress $deliverToAddress): self
    {
        $this->deliverToAddress = $deliverToAddress;

        return $this;
    }
}
