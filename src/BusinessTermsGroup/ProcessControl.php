<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;

/**
 * BG-2
 * A group of business terms providing information on the business process and rules applicable to the Invoice document.
 */
class ProcessControl
{
    /**
     * BT-23
     * Identifies the business process context in which the transaction appears,
     * to enable the Buyer to process the Invoice in an appropriate way.
     */
    private ?string $businessProcessType;

    /**
     * BT-24
     * An identification of the specification containing the total set of rules regarding semantic content,
     * cardinalities and business rules to which the data contained in the instance document conforms.
     */
    private SpecificationIdentifier $specificationIdentifier;

    public function __construct(SpecificationIdentifier $specificationIdentifier)
    {
        $this->specificationIdentifier = $specificationIdentifier;
    }

    public function getBusinessProcessType(): ?string
    {
        return $this->businessProcessType;
    }

    public function setBusinessProcessType(?string $businessProcessType): self
    {
        $this->businessProcessType = $businessProcessType;

        return $this;
    }

    public function getSpecificationIdentifier(): Specificationidentifier
    {
        return $this->specificationIdentifier;
    }

    public function setSpecificationIdentifier(SpecificationIdentifier $specificationIdentifier): self
    {
        $this->specificationIdentifier = $specificationIdentifier;

        return $this;
    }
}
