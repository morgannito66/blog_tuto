<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
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
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ipClient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Article;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PaymentCommande", inversedBy="commande", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentCommande;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beDownload;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ipDownload;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIpClient(): ?string
    {
        return $this->ipClient;
    }

    public function setIpClient(string $ipClient): self
    {
        $this->ipClient = $ipClient;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->Article;
    }

    public function setArticle(?Article $Article): self
    {
        $this->Article = $Article;

        return $this;
    }

    public function getPaymentCommande(): ?PaymentCommande
    {
        return $this->paymentCommande;
    }

    public function setPaymentCommande(PaymentCommande $paymentCommande): self
    {
        $this->paymentCommande = $paymentCommande;

        return $this;
    }

    public function getBeDownload(): ?bool
    {
        return $this->beDownload;
    }

    public function setBeDownload(?bool $beDownload): self
    {
        $this->beDownload = $beDownload;

        return $this;
    }

    public function getIpDownload(): ?string
    {
        return $this->ipDownload;
    }

    public function setIpDownload(?string $ipDownload): self
    {
        $this->ipDownload = $ipDownload;

        return $this;
    }
}
