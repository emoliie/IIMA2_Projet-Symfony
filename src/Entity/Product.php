<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(type: Types::BLOB)]
    private $image;

    /**
     * @var Collection<int, BasketContent>
     */
    #[ORM\OneToMany(targetEntity: BasketContent::class, mappedBy: 'product')]
    private Collection $basketContents;

    public function __construct()
    {
        $this->basketContents = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, BasketContent>
     */
    public function getBasketContents(): Collection
    {
        return $this->basketContents;
    }

    public function addBasketContent(BasketContent $basketContent): static
    {
        if (!$this->basketContents->contains($basketContent)) {
            $this->basketContents->add($basketContent);
            $basketContent->setProduct($this);
        }

        return $this;
    }

    public function removeBasketContent(BasketContent $basketContent): static
    {
        if ($this->basketContents->removeElement($basketContent)) {
            // set the owning side to null (unless already changed)
            if ($basketContent->getProduct() === $this) {
                $basketContent->setProduct(null);
            }
        }

        return $this;
    }
}
