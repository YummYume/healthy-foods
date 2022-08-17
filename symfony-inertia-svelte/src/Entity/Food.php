<?php

namespace App\Entity;

use App\Entity\Traits\CsrfProtectedTrait;
use App\Repository\FoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
#[UniqueEntity(['name', 'brand'], errorPath: 'name', message: 'food.unique')]
class Food
{
    use CsrfProtectedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['food', 'category', 'brand'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: 'food.name.max_length')]
    #[Assert\NotBlank(allowNull: false, message: 'food.name.not_blank')]
    #[Groups(['food', 'category', 'brand'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: 'food.calories.positive_or_zero')]
    #[Groups(['food', 'category', 'brand'])]
    private ?float $calories = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'foods')]
    #[Groups(['food', 'brand'])]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'foods')]
    #[Groups(['food', 'category'])]
    private ?Brand $brand = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCalories(): ?float
    {
        return $this->calories;
    }

    public function setCalories(?float $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
