<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Identifier\InvoiceLineIdentifier;
use Tiime\EN16931\DataType\Identifier\ObjectIdentifier;
use Tiime\EN16931\DataType\Reference\PurchaseOrderLineReference;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\Quantity;

/**
 * BG-25
 * A group of business terms providing information on individual Invoice lines.
 *
 * Groupe de termes métiers fournissant des informations sur les lignes de Facture individuelles.
 */
class InvoiceLine
{
    /**
     * BT-126
     * A unique identifier for the individual line within the Invoice.
     *
     * Identifiant unique d'une ligne au sein de la Facture.
     */
    private InvoiceLineIdentifier $identifier;

    /**
     * BT-127
     * A textual note that gives unstructured information that is relevant to the Invoice line.
     *
     * Commentaire fournissant des informations non structurées concernant la ligne de Facture.
     */
    private ?string $note;

    /**
     * BT-128
     * An identifier for an object on which the invoice line is based, given by the Seller.
     * It may be a subscription number, telephone number, meter point etc., as applicable.
     * If it may be not clear for the receiver what scheme is used for the identifier,
     * a conditional scheme identifier should be used that shall be chosen from the UNTDID 1153 code list entries.
     *
     * Identifiant d'un objet sur lequel est basée la ligne de facture et qui est indiqué par le Vendeur.
     * Il peut s'agir d'un numéro d'abonnement, d'un numéro de téléphone, d'un compteur, etc., selon le cas.
     * Si le schéma utilisé pour l'identifiant n'est pas clair pour le destinataire,
     * il convient d'utiliser un identifiant de schéma conditionnel qui doit être choisi parmi
     * les codes de la liste de codes de la donnée 1153 du dictionnaire des données UNTDID.
     */
    private ?ObjectIdentifier $objectIdentifier;

    /**
     * BT-129
     * The quantity of items (goods or services) that is charged in the Invoice line.
     *
     * Quantité d'articles (biens ou services) facturée prise en compte dans la ligne de Facture.
     */
    private Quantity $invoicedQuantity;

    /**
     * BT-130
     * The unit of measure that applies to the invoiced quantity.
     *
     * Unité de mesure applicable à la quantité facturée.
     */
    private UnitOfMeasurement $invoicedQuantityUnitOfMeasureCode;

    /**
     * BT-131
     * The total amount of the Invoice line.
     *
     * Montant total de la ligne de Facture.
     */
    private Amount $netAmount;

    /**
     * BT-132
     * An identifier for a referenced line within a purchase order, issued by the Buyer.
     *
     * Identifiant d'une ligne d'un bon de commande référencée, généré par l'Acheteur.
     */
    private ?PurchaseOrderLineReference $referencedPurchaseOrderLineReference;

    /**
     * BT-133
     * A textual value that specifies where to book the relevant data into the Buyer's financial accounts.
     *
     * Valeur textuelle spécifiant où imputer les données pertinentes dans les comptes comptables de l'Acheteur.
     */
    private ?string $buyerAccountingReference;

    /**
     * BG-26
     * A group of business terms providing information about the period relevant for the Invoice line.
     */
    private ?InvoiceLinePeriod $period;

    /**
     * BG-27
     * A group of business terms providing information about allowances applicable to the individual Invoice line.
     *
     * @var array<int, InvoiceLineAllowance>
     */
    private array $allowances;

    /**
     * BG-28
     * A group of business terms providing information about charges and taxes other than VAT applicable to
     * the individual Invoice line.
     *
     * @var array<int, InvoiceLineCharge>
     */
    private array $charges;

    /**
     * BG-29
     * A group of business terms providing information about the price applied for
     * the goods and services invoiced on the Invoice line.
     */
    private PriceDetails $priceDetails;

    /**
     * BG-30
     * A group of business terms providing information about the VAT applicable for
     * the goods and services invoiced on the Invoice line.
     */
    private LineVatInformation $lineVatInformation;

    /**
     * BG-31
     * A group of business terms providing information about the goods and services invoiced.
     */
    private ItemInformation $itemInformation;



    public function __construct(
        InvoiceLineIdentifier $identifier,
        float $invoicedQuantity,
        UnitOfMeasurement $invoicedQuantityUnitOfMeasureCode,
        float $netAmount,
        PriceDetails $priceDetails,
        LineVatInformation $lineVatInformation,
        ItemInformation $itemInformation,
    ) {
        $this->identifier = $identifier;
        $this->invoicedQuantity = new Quantity($invoicedQuantity);
        $this->invoicedQuantityUnitOfMeasureCode = $invoicedQuantityUnitOfMeasureCode;
        $this->netAmount = new Amount($netAmount);
        $this->priceDetails = $priceDetails;
        $this->lineVatInformation = $lineVatInformation;
        $this->itemInformation = $itemInformation;
        $this->allowances = [];
        $this->charges = [];
        $this->note = null;
        $this->objectIdentifier = null;
        $this->referencedPurchaseOrderLineReference = null;
        $this->buyerAccountingReference = null;
        $this->period = null;
    }

    public function getIdentifier(): InvoiceLineIdentifier
    {
        return $this->identifier;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getObjectIdentifier(): ?ObjectIdentifier
    {
        return $this->objectIdentifier;
    }

    public function setObjectIdentifier(?ObjectIdentifier $objectIdentifier): self
    {
        $this->objectIdentifier = $objectIdentifier;

        return $this;
    }

    public function getInvoicedQuantity(): Quantity
    {
        return $this->invoicedQuantity;
    }

    public function getInvoicedQuantityUnitOfMeasureCode(): UnitOfMeasurement
    {
        return $this->invoicedQuantityUnitOfMeasureCode;
    }

    public function getNetAmount(): Amount
    {
        return $this->netAmount;
    }

    public function getReferencedPurchaseOrderLineReference(): ?PurchaseOrderLineReference
    {
        return $this->referencedPurchaseOrderLineReference;
    }

    public function setReferencedPurchaseOrderLineReference(
        ?PurchaseOrderLineReference $referencedPurchaseOrderLineReference
    ): self {
        $this->referencedPurchaseOrderLineReference = $referencedPurchaseOrderLineReference;

        return $this;
    }

    public function getBuyerAccountingReference(): ?string
    {
        return $this->buyerAccountingReference;
    }

    public function setBuyerAccountingReference(?string $buyerAccountingReference): self
    {
        $this->buyerAccountingReference = $buyerAccountingReference;

        return $this;
    }

    public function getPeriod(): ?InvoiceLinePeriod
    {
        return $this->period;
    }

    public function setPeriod(?InvoiceLinePeriod $period): self
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @return array<int, InvoiceLineAllowance>
     */
    public function getAllowances(): array
    {
        return $this->allowances;
    }

    /**
     * @param array<int, InvoiceLineAllowance> $allowances
     */
    public function setAllowances(array $allowances): self
    {
        $this->allowances = $allowances;

        return $this;
    }

    /**
     * @return array<int, InvoiceLineCharge>
     */
    public function getCharges(): array
    {
        return $this->charges;
    }

    /**
     * @param array<int, InvoiceLineCharge> $charges
     */
    public function setCharges(array $charges): self
    {
        $this->charges = $charges;

        return $this;
    }

    public function getPriceDetails(): PriceDetails
    {
        return $this->priceDetails;
    }

    public function getLineVatInformation(): LineVatInformation
    {
        return $this->lineVatInformation;
    }

    public function getItemInformation(): ItemInformation
    {
        return $this->itemInformation;
    }
}
