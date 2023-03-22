<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

/**
 * BG-9
 * A group of business terms providing contact information relevant for the Buyer.
 */
class BuyerContact
{
    /**
     * BT-56
     * A contact point for a legal entity or person.
     */
    private string $point;

    /**
     * BT-57
     * A phone number for the contact point.
     */
    private ?string $phoneNumber;

    /**
     * BT-58
     * An e-mail address for the contact point.
     */
    private ?string $email;

    public function __construct(string $point, ?string $phoneNumber, ?string $email)
    {
        if (!is_string($phoneNumber) && !is_string($email)) {
            throw new \Exception('@todo');
        }

        $this->point = $point;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
    }

    public function getPoint(): string
    {
        return $this->point;
    }

    public function setPoint(string $point): self
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
        if (!is_string($phoneNumber) && !is_string($this->email)) {
            throw new \Exception('@todo');
        }

        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        if (!is_string($this->phoneNumber) && !is_string($email)) {
            throw new \Exception('@todo');
        }

        $this->email = $email;

        return $this;
    }
}
