<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Identifier\PaymentAccountIdentifier;
use Tiime\EN16931\DataType\Identifier\PaymentServiceProviderIdentifier;

/**
 * BG-17
 * A group of business terms to specify credit transfer payments.
 */
class CreditTransfer
{
    /**
     * BT-84
     * A unique identifier of the financial payment account, at a payment service provider,
     * to which payment should be made.
     */
    private PaymentAccountIdentifier $paymentAccountIdentifier;

    /**
     * BT-85
     * The name of the payment account, at a payment service provider, to which payment should be made.
     */
    private ?string $paymentAccountName;

    /**
     * BT-86
     * An identifier for the payment service provider where a payment account is located.
     */
    private ?PaymentServiceProviderIdentifier $paymentServiceProviderIdentifier;

    public function __construct(PaymentAccountIdentifier $paymentAccountIdentifier)
    {
        $this->paymentAccountIdentifier = $paymentAccountIdentifier;
        $this->paymentAccountName = null;
        $this->paymentServiceProviderIdentifier = null;
    }

    public function getPaymentAccountIdentifier(): PaymentAccountIdentifier
    {
        return $this->paymentAccountIdentifier;
    }

    public function setPaymentAccountIdentifier(PaymentAccountIdentifier $paymentAccountIdentifier): self
    {
        $this->paymentAccountIdentifier = $paymentAccountIdentifier;

        return $this;
    }

    public function getPaymentAccountName(): ?string
    {
        return $this->paymentAccountName;
    }

    public function setPaymentAccountName(?string $paymentAccountName): self
    {
        $this->paymentAccountName = $paymentAccountName;

        return $this;
    }

    public function getPaymentServiceProviderIdentifier(): ?PaymentServiceProviderIdentifier
    {
        return $this->paymentServiceProviderIdentifier;
    }

    public function setPaymentServiceProviderIdentifier(
        ?PaymentServiceProviderIdentifier $paymentServiceProviderIdentifier
    ): self {
        $this->paymentServiceProviderIdentifier = $paymentServiceProviderIdentifier;

        return $this;
    }
}
