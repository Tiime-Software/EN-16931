<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

/**
 * BG-6
 * A group of business terms providing contact information about the Seller.
 */
class SellerContact
{
    /**
     * BT-41
     * A contact point for a legal entity or person.
     */
    private ?string $point;

    /**
     * BT-42
     * A phone number for the contact point.
     */
    private ?string $phoneNumber;

    /**
     * BT-43
     * An e-mail address for the contact point.
     */
    private ?string $email;

    public function __construct()
    {
        $this->point = null;
        $this->phoneNumber = null;
        $this->email = null;
    }

    public function getPoint(): ?string
    {
        return $this->point;
    }

    public function setPoint(?string $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
