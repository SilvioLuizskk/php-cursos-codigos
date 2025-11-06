<?php

namespace App\Domain\Product;

use InvalidArgumentException;

/**
 * Entidade de domínio Product (não é Eloquent) que contém invariantes e comportamento
 * de negócio. Serve como objeto rico para testes e para separar regras de domínio da
 * camada de persistência.
 */
final class ProductEntity
{
    private string $name;
    private ?string $description;
    private float $price;
    private string $sku;
    private int $stock;
    private bool $active;
    private ?int $categoryId;
    private ?int $userId;

    public function __construct(string $name, float $price, string $sku, int $stock = 0, ?string $description = null, bool $active = true, ?int $categoryId = null, ?int $userId = null)
    {
        $this->setName($name);
        $this->setPrice($price);
        $this->setSku($sku);
        $this->setStock($stock);
        $this->description = $description;
        $this->active = $active;
        $this->categoryId = $categoryId;
        $this->userId = $userId;
    }

    // Fábricas
    public static function fromArray(array $data): self
    {
        return new self(
            (string)($data['name'] ?? ''),
            isset($data['price']) ? (float)$data['price'] : 0.0,
            (string)($data['sku'] ?? ''),
            isset($data['stock']) ? (int)$data['stock'] : 0,
            $data['description'] ?? null,
            isset($data['active']) ? (bool)$data['active'] : true,
            $data['category_id'] ?? null,
            $data['user_id'] ?? null
        );
    }

    // Getters
    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function stock(): int
    {
        return $this->stock;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function categoryId(): ?int
    {
        return $this->categoryId;
    }

    public function userId(): ?int
    {
        return $this->userId;
    }

    // Mutators with invariants
    public function setName(string $name): void
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('O nome do produto não pode ser vazio.');
        }
        $this->name = $name;
    }

    public function setPrice(float $price): void
    {
        if (!is_finite($price) || $price < 0) {
            throw new InvalidArgumentException('O preço deve ser um número maior ou igual a zero.');
        }
        $this->price = $price;
    }

    public function setSku(string $sku): void
    {
        $sku = trim($sku);
        if ($sku === '') {
            throw new InvalidArgumentException('SKU não pode ser vazio.');
        }
        $this->sku = $sku;
    }

    public function setStock(int $stock): void
    {
        if ($stock < 0) {
            throw new InvalidArgumentException('Stock não pode ser negativo.');
        }
        $this->stock = $stock;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function deactivate(): void
    {
        $this->active = false;
    }

    public function increaseStock(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('A quantidade para aumentar o stock deve ser maior que zero.');
        }
        $this->stock += $amount;
    }

    public function decreaseStock(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('A quantidade para diminuir o stock deve ser maior que zero.');
        }
        if ($this->stock - $amount < 0) {
            throw new InvalidArgumentException('Não é possível reduzir o stock abaixo de zero.');
        }
        $this->stock -= $amount;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'sku' => $this->sku,
            'stock_quantity' => $this->stock,
            'active' => $this->active,
            'category_id' => $this->categoryId,
            'user_id' => $this->userId,
        ];
    }
}
