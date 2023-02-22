<?php

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\PaymentMeansCode;

/**
 * BG-16
 * A group of business terms providing information about the payment.
 */
class PaymentInstructions
{
    /**
     * BT-81
     * The means, expressed as code, for how a payment is expected to be or has been settled.
     */
    private PaymentMeansCode $paymentMeansTypeCode;

    /**
     * BT-82
     * The means, expressed as text, for how a payment is expected to be or has been settled.
     */
    private ?string $paymentMeansText;

    /**
     * BT-83
     * A textual value used to establish a link between the payment and the Invoice, issued by the Seller.
     */
    private ?string $remittanceInformation;

    /**
     * @var array<int, CreditTransfer>
     */
    private array $creditTransfers;

    private ?PaymentCardInformation $paymentCardInformation;

    private ?DirectDebit $directDebit;

    public function __construct(PaymentMeansCode $paymentMeansTypeCode)
    {
        $this->paymentMeansTypeCode = $paymentMeansTypeCode;
        $this->paymentMeansText = null;
        $this->remittanceInformation = null;
        $this->creditTransfers = [];
        $this->paymentCardInformation = null;
        $this->directDebit = null;
    }

    public function getPaymentMeansTypeCode(): PaymentMeansCode
    {
        return $this->paymentMeansTypeCode;
    }

    public function setPaymentMeansTypeCode(PaymentMeansCode $paymentMeansTypeCode): self
    {
        $this->paymentMeansTypeCode = $paymentMeansTypeCode;

        return $this;
    }

    public function getPaymentMeansText(): ?string
    {
        return $this->paymentMeansText;
    }

    public function setPaymentMeansText(?string $paymentMeansText): self
    {
        $this->paymentMeansText = $paymentMeansText;

        return $this;
    }

    public function getRemittanceInformation(): ?string
    {
        return $this->remittanceInformation;
    }

    public function setRemittanceInformation(?string $remittanceInformation): self
    {
        $this->remittanceInformation = $remittanceInformation;

        return $this;
    }

    /**
     * @return array<int, CreditTransfer>
     */
    public function getCreditTransfers(): array
    {
        return $this->creditTransfers;
    }

    /**
     * @param array<int, CreditTransfer> $creditTransfers
     */
    public function setCreditTransfers(array $creditTransfers): self
    {
        $this->creditTransfers = $creditTransfers;

        return $this;
    }

    public function getPaymentCardInformation(): ?PaymentCardInformation
    {
        return $this->paymentCardInformation;
    }

    public function setPaymentCardInformation(?PaymentCardInformation $paymentCardInformation): self
    {
        $this->paymentCardInformation = $paymentCardInformation;

        return $this;
    }

    public function getDirectDebit(): ?DirectDebit
    {
        return $this->directDebit;
    }

    public function setDirectDebit(?DirectDebit $directDebit): self
    {
        $this->directDebit = $directDebit;

        return $this;
    }
}
