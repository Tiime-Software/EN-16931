<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

/**
 * BG-18
 * A group of business terms providing information about card used for payment contemporaneous with invoice issuance.
 */
class PaymentCardInformation
{
    /**
     * BT-87
     * The Primary Account Number (PAN) of the card used for payment.
     */
    private string $primaryAccountNumber;

    /**
     * BT-88
     * The name of the payment card holder.
     */
    private ?string $holderName;

    public function __construct(string $primaryAccountNumber)
    {
        if (!preg_match('/^[^0-9]*\d{4,6}$/', $primaryAccountNumber)) {
            throw new \Exception('@todo');
        }

        $this->primaryAccountNumber = $primaryAccountNumber;
        $this->holderName = null;
    }

    public function getPrimaryAccountNumber(): string
    {
        return $this->primaryAccountNumber;
    }

    public function getHolderName(): ?string
    {
        return $this->holderName;
    }

    public function setHolderName(?string $holderName): self
    {
        $this->holderName = $holderName;

        return $this;
    }
}
