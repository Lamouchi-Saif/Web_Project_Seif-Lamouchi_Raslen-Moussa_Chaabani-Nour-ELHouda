<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $name = null;

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank(message: "Ingredient name cannot be blank")]
    #[Assert\Length(
        min: 2,
        max: 45,
        minMessage: "Ingredient name must be at least {{ limit }} characters long",
        maxMessage: "Ingredient name cannot be longer than {{ limit }} characters"
    )]
    private ?string $type = null;

    /**
     * @var Collection<int, ProductIngredient>
     */
    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: ProductIngredient::class)]
    private Collection $productIngredients;

    #[ORM\OneToOne(targetEntity: IngredientStock::class, mappedBy: 'ingredient', cascade: ['persist', 'remove'])]
    private ?IngredientStock $ingredientStock = null;

    public function __construct()
    {
        $this->productIngredients = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, ProductIngredient>
     */
    public function getProductIngredients(): Collection
    {
        return $this->productIngredients;
    }

    public function addProductIngredient(ProductIngredient $productIngredient): static
    {
        if (!$this->productIngredients->contains($productIngredient)) {
            $this->productIngredients->add($productIngredient);
            $productIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removeProductIngredient(ProductIngredient $productIngredient): static
    {
        if ($this->productIngredients->removeElement($productIngredient)) {
            // set the owning side to null (unless already changed)
            if ($productIngredient->getIngredient() === $this) {
                $productIngredient->setIngredient(null);
            }
        }

        return $this;
    }

    public function getIngredientStock(): ?IngredientStock
    {
        return $this->ingredientStock;
    }

    public function setIngredientStock(?IngredientStock $ingredientStock): static
    {
        // set the owning side of the relation if necessary
        if ($ingredientStock !== null && $ingredientStock->getIngredient() !== $this) {
            $ingredientStock->setIngredient($this);
        }

        $this->ingredientStock = $ingredientStock;

        return $this;
    }
}
