<?php

namespace App\Entity;

use App\Entity\Traits\CsrfProtectedTrait;
use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[UniqueEntity(['name'], message: 'brand.unique')]
class Brand
{
    use CsrfProtectedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['food', 'category', 'brand'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: 'brand.string.max_length')]
    #[Assert\NotBlank(allowNull: false, message: 'brand.name.not_blank')]
    #[Groups(['food', 'category', 'brand'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Food::class)]
    #[Groups(['brand'])]
    private Collection $foods;

    public function __construct()
    {
        $this->foods = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFoods(): Collection
    {
        return $this->foods;
    }

    public function addFood(Food $food): self
    {
        if (!$this->foods->contains($food)) {
            $this->foods->add($food);
            $food->setBrand($this);
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        if ($this->foods->removeElement($food)) {
            // set the owning side to null (unless already changed)
            if ($food->getBrand() === $this) {
                $food->setBrand(null);
            }
        }

        return $this;
    }
}
