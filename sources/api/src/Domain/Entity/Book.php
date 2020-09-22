<?php

namespace Ddd\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * Category[]
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="books_categories",
     *      joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     * @Serializer\Type("ArrayCollection<string>")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = new ArrayCollection($categories);
    }

    public function addCategory(Category $category): void
    {
        $ids = array_map(fn (Category $category) => $category->getId(), $this->categories->toArray());

        if (\in_array($category->getId(), $ids, true)) {
            return;
        }

        $this->categories->add($category);
    }
}
