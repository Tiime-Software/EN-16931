<?php

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Identifier\LegalRegistrationIdentifier;
use Tiime\EN16931\DataType\Identifier\PayeeIdentifier;

/**
 * BG-10
 * A group of business terms providing information about the Payee, i.e. the role that receives the payment.
 */
class Payee
{
    /**
     * BT-59
     * The name of the Payee.
     */
    private string $name;

    /**
     * BT-60
     * An identifier for the Payee.
     */
    private ?PayeeIdentifier $identifier;

    /**
     * BT-61
     * An identifier issued by an official registrar that identifies the Payee as a legal entity or person.
     */
    private ?LegalRegistrationIdentifier $legalRegistrationIdentifier;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->identifier = null;
        $this->legalRegistrationIdentifier = null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdentifier(): ?PayeeIdentifier
    {
        return $this->identifier;
    }

    public function setIdentifier(?PayeeIdentifier $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getLegalRegistrationIdentifier(): ?LegalRegistrationIdentifier
    {
        return $this->legalRegistrationIdentifier;
    }

    public function setLegalRegistrationIdentifier(?LegalRegistrationIdentifier $legalRegistrationIdentifier): self
    {
        $this->legalRegistrationIdentifier = $legalRegistrationIdentifier;

        return $this;
    }
}
