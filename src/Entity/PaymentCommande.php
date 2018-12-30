<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentCommandeRepository")
 */
class PaymentCommande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $object;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cardId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cardBrand;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cardCountry;

    /**
     * @ORM\Column(type="integer")
     */
    private $cardExpMonth;

    /**
     * @ORM\Column(type="integer")
     */
    private $cardExpYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $cardLast4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Commande", mappedBy="paymentCommande", cascade={"persist", "remove"})
     */
    private $commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentId(): ?string
    {
        return $this->payment_id;
    }

    public function setPaymentId(string $payment_id): self
    {
        $this->payment_id = $payment_id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCardId(): ?string
    {
        return $this->cardId;
    }

    public function setCardId(string $cardId): self
    {
        $this->cardId = $cardId;

        return $this;
    }

    public function getCardBrand(): ?string
    {
        return $this->cardBrand;
    }

    public function setCardBrand(string $cardBrand): self
    {
        $this->cardBrand = $cardBrand;

        return $this;
    }

    public function getCardCountry(): ?string
    {
        return $this->cardCountry;
    }

    public function setCardCountry(string $cardCountry): self
    {
        $this->cardCountry = $cardCountry;

        return $this;
    }

    public function getCardExpMonth(): ?int
    {
        return $this->cardExpMonth;
    }

    public function setCardExpMonth(int $cardExpMonth): self
    {
        $this->cardExpMonth = $cardExpMonth;

        return $this;
    }

    public function getCardExpYear(): ?int
    {
        return $this->cardExpYear;
    }

    public function setCardExpYear(int $cardExpYear): self
    {
        $this->cardExpYear = $cardExpYear;

        return $this;
    }

    public function getCardLast4(): ?int
    {
        return $this->cardLast4;
    }

    public function setCardLast4(int $cardLast4): self
    {
        $this->cardLast4 = $cardLast4;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;

        // set the owning side of the relation if necessary
        if ($this !== $commande->getPaymentCommande()) {
            $commande->setPaymentCommande($this);
        }

        return $this;
    }
}
