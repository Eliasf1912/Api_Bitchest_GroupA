<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on:"create")]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on:"update")]
    private ?\DateTimeImmutable $UpdatedAt = null;

    #[ORM\Column]
    private ?int $Balance = null;

    /**
     * @var Collection<int, Transactions>
     */
    #[ORM\OneToMany(targetEntity: Transactions::class, mappedBy: 'wallet')]
    private Collection $Transactions;

    /**
     * @var Collection<int, CryptoWallet>
     */
    #[ORM\OneToMany(targetEntity: CryptoWallet::class, mappedBy: 'wallet',cascade: ['persist'])]
    private Collection $CryptoWallet;

    public function __construct()
    {
        $this->Transactions = new ArrayCollection();
        $this->CryptoWallet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $UpdatedAt): static
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->Balance;
    }

    public function setBalance(int $Balance): static
    {
        $this->Balance = $Balance;

        return $this;
    }

    /**
     * @return Collection<int, Transactions>
     */
    public function getTransactions(): Collection
    {
        return $this->Transactions;
    }

    public function addTransaction(Transactions $transaction): static
    {
        if (!$this->Transactions->contains($transaction)) {
            $this->Transactions->add($transaction);
            $transaction->setWallet($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): static
    {
        if ($this->Transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getWallet() === $this) {
                $transaction->setWallet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CryptoWallet>
     */
    public function getCryptoWallet(): Collection
    {
        return $this->CryptoWallet;
    }

    public function addCryptoWallet(CryptoWallet $cryptoWallet): static
    {
        if (!$this->CryptoWallet->contains($cryptoWallet)) {
            $this->CryptoWallet->add($cryptoWallet);
            $cryptoWallet->setWallet($this);
        }

        return $this;
    }

    public function removeCryptoWallet(CryptoWallet $cryptoWallet): static
    {
        if ($this->CryptoWallet->removeElement($cryptoWallet)) {
            // set the owning side to null (unless already changed)
            if ($cryptoWallet->getWallet() === $this) {
                $cryptoWallet->setWallet(null);
            }
        }

        return $this;
    }


}
