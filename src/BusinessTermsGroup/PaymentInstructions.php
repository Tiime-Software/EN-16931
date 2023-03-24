<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\PaymentMeansCode;

/**
 * BG-16
 * A group of business terms providing information about the payment.
 */
class PaymentInstructions
{
    private const CREDIT_TRANSFER_CODES = [PaymentMeansCode::CREDIT_TRANSFER, PaymentMeansCode::SEPA_CREDIT_TRANSFER];

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
     * BG-17
     * A group of business terms to specify credit transfer payments.
     *
     * @var array<int, CreditTransfer>
     */
    private array $creditTransfers;

    /**
     * BG-18
     * A group of business terms providing information about card used for payment contemporaneous with invoice issuance.
     */
    private ?PaymentCardInformation $paymentCardInformation;

    /**
     * BG-19
     * A group of business terms to specify a direct debit.
     */
    private ?DirectDebit $directDebit;

    /**
     * @param array<int, CreditTransfer> $creditTransfers
     */
    public function __construct(PaymentMeansCode $paymentMeansTypeCode, array $creditTransfers = [])
    {
        $this->paymentMeansTypeCode = $paymentMeansTypeCode;
        $this->paymentMeansText = null;
        $this->remittanceInformation = null;
        $this->setCreditTransfers($creditTransfers);

        $this->paymentCardInformation = null;
        $this->directDebit = null;
    }

    public function getPaymentMeansTypeCode(): PaymentMeansCode
    {
        return $this->paymentMeansTypeCode;
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
        if (
            \count($creditTransfers) === 0
            && \in_array($this->paymentMeansTypeCode, self::CREDIT_TRANSFER_CODES, true)
        ) {
            throw new \Exception('@todo');
        }

        $this->creditTransfers = [];

        foreach ($creditTransfers as $creditTransfer) {
            $this->addCreditTransfer($creditTransfer);
        }

        return $this;
    }

    private function addCreditTransfer(CreditTransfer $creditTransfer): void
    {
        $this->creditTransfers[] = $creditTransfer;
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
