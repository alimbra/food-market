<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'supplier')]
    private Collection $products;

    /**
     * @var Collection<int, ImportFile>
     */
    #[ORM\OneToMany(targetEntity: ImportFile::class, mappedBy: 'supplier')]
    private Collection $importFiles;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->importFiles = new ArrayCollection();
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

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setSupplier($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSupplier() === $this) {
                $product->setSupplier(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }

    /**
     * @return Collection<int, ImportFile>
     */
    public function getImportFiles(): Collection
    {
        return $this->importFiles;
    }

    public function addImportFile(ImportFile $importFile): static
    {
        if (!$this->importFiles->contains($importFile)) {
            $this->importFiles->add($importFile);
            $importFile->setSupplier($this);
        }

        return $this;
    }

    public function removeImportFile(ImportFile $importFile): static
    {
        if ($this->importFiles->removeElement($importFile)) {
            // set the owning side to null (unless already changed)
            if ($importFile->getSupplier() === $this) {
                $importFile->setSupplier(null);
            }
        }

        return $this;
    }
}
