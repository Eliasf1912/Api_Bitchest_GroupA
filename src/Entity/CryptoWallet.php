<?php

namespace App\Entity;

use App\Repository\CryptoWalletRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CryptoWalletRepository::class)]
class CryptoWallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user:cryptos')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('user:cryptos')]
    private ?string $crypto = null;

    #[ORM\Column]
    #[Groups('user:cryptos')]
    private ?float $Amount = null;

    #[ORM\ManyToOne(inversedBy: 'CryptoWallet')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $wallet = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    #[Groups('user:cryptos')]
    private ?string $Cotation = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on:"update")]
    #[Groups('user:cryptos')]
    private ?\DateTimeImmutable $updated_At = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on:"create")]
    #[Groups('user:cryptos')]
    private ?\DateTimeImmutable $created_At = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    #[Groups('user:cryptos')]
    private ?string $Price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrypto(): ?string
    {
        return $this->crypto;
    }

    public function setCrypto(string $crypto): static
    {
        $this->crypto = $crypto;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->Amount;
    }

    public function setAmount(float $Amount): static
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getCotation(): ?string
    {
        return $this->Cotation;
    }

    public function setCotation(string $Cotation): static
    {
        $this->Cotation = $Cotation;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_At;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_At): static
    {
        $this->updated_At = $updated_At;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_At;
    }

    public function setCreatedAt(\DateTimeImmutable $created_At): static
    {
        $this->created_At = $created_At;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->Price;
    }

    public function setPrice(string $Price): static
    {
        $this->Price = $Price;

        return $this;
    }
}
