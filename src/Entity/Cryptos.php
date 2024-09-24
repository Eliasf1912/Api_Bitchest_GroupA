<?php

namespace App\Entity;

use App\Repository\CryptosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CryptosRepository::class)]
class Cryptos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('user:cotations')]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups('user:cotations')]
    private ?float $price = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: "create")]
    private ?\DateTimeImmutable $CreatedAt = null;
 
    #[ORM\Column]
    #[Gedmo\Timestampable(on: "update")]
    private ?\DateTimeImmutable $UpdatedAt = null;

    /**
     * @var Collection<int, CryptoCotations>
     */
    #[ORM\OneToMany(targetEntity: CryptoCotations::class, mappedBy: 'cryptos', orphanRemoval: true, cascade: ['persist','remove'])]
    private Collection $Cotations;

    public function __construct()
    {
        $this->Cotations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
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

    /**
     * @return Collection<int, CryptoCotations>
     */
    public function getCotations(): Collection
    {
        return $this->Cotations;
    }

    public function addCotation(CryptoCotations $cotation): static
    {
        if (!$this->Cotations->contains($cotation)) {
            $this->Cotations->add($cotation);
            $cotation->setCryptos($this);
        }

        return $this;
    }

    public function removeCotation(CryptoCotations $cotation): static
    {
        if ($this->Cotations->removeElement($cotation)) {
            // set the owning side to null (unless already changed)
            if ($cotation->getCryptos() === $this) {
                $cotation->setCryptos(null);
            }
        }

        return $this;
    }
}
