<?php

namespace App\Entity;

use App\Repository\BasketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
class Basket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'baskets')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $status = null;

    /**
     * @var Collection<int, BasketContent>
     */
    #[ORM\OneToMany(targetEntity: BasketContent::class, mappedBy: 'basket', cascade: ['persist', 'remove'])]
    private Collection $basketContents;

    public function __construct()
    {
        $this->basketContents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

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
            $basketContent->setBasket($this);
        }

        return $this;
    }

    public function removeBasketContent(BasketContent $basketContent): static
    {
        if ($this->basketContents->removeElement($basketContent)) {
            // set the owning side to null (unless already changed)
            if ($basketContent->getBasket() === $this) {
                $basketContent->setBasket(null);
            }
        }

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->basketContents as $content) {
            $total += $content->getProduct()->getPrice() * $content->getQuantity();
        }

        return $total;
    }

    public function getItemCount(): int
    {
        $count = 0;

        foreach ($this->basketContents as $content) {
            $count += $content->getQuantity();
        }

        return $count;
    }

    #[ORM\Column(type: 'integer')]
    private ?int $userOrderNumber = null;

    public function getUserOrderNumber(): ?int
    {
        return $this->userOrderNumber;
    }

    public function setUserOrderNumber(int $userOrderNumber): static
    {
        $this->userOrderNumber = $userOrderNumber;

        return $this;
    }
}
