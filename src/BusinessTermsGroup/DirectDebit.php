<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Identifier\BankAssignedCreditorIdentifier;
use Tiime\EN16931\DataType\Identifier\DebitedAccountIdentifier;
use Tiime\EN16931\DataType\Identifier\MandateReferenceIdentifier;

/**
 * BG-19
 * A group of business terms to specify a direct debit.
 */
class DirectDebit
{
    /**
     * BT-89
     * Unique identifier assigned by the Payee for referencing the direct debit mandate.
     */
    private ?MandateReferenceIdentifier $mandateReferenceIdentifier;

    /**
     * BT-90
     * Unique banking reference identifier of the Payee or Seller assigned by the Payee or Seller bank.
     */
    private ?BankAssignedCreditorIdentifier $bankAssignedCreditorIdentifier;

    /**
     * BT-91
     * The account to be debited by the direct debit.
     */
    private ?DebitedAccountIdentifier $debitedAccountIdentifier;

    public function __construct()
    {
        $this->mandateReferenceIdentifier = null;
        $this->bankAssignedCreditorIdentifier = null;
        $this->debitedAccountIdentifier = null;
    }

    public function getMandateReferenceIdentifier(): ?MandateReferenceIdentifier
    {
        return $this->mandateReferenceIdentifier;
    }

    public function setMandateReferenceIdentifier(?MandateReferenceIdentifier $mandateReferenceIdentifier): DirectDebit
    {
        $this->mandateReferenceIdentifier = $mandateReferenceIdentifier;

        return $this;
    }

    public function getBankAssignedCreditorIdentifier(): ?BankAssignedCreditorIdentifier
    {
        return $this->bankAssignedCreditorIdentifier;
    }

    public function setBankAssignedCreditorIdentifier(
        ?BankAssignedCreditorIdentifier $bankAssignedCreditorIdentifier
    ): DirectDebit {
        $this->bankAssignedCreditorIdentifier = $bankAssignedCreditorIdentifier;

        return $this;
    }

    public function getDebitedAccountIdentifier(): ?DebitedAccountIdentifier
    {
        return $this->debitedAccountIdentifier;
    }

    public function setDebitedAccountIdentifier(?DebitedAccountIdentifier $debitedAccountIdentifier): DirectDebit
    {
        $this->debitedAccountIdentifier = $debitedAccountIdentifier;

        return $this;
    }
}
