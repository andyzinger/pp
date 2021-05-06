<?php


namespace Kernel\DTO;


class Product
{
    private int $id;
    private string $name;
    private int $categoryId;
    private ?string $picture;
    private int $price;

    public function __construct(int $id, string $name, int $categoryId, ?string $picture, int $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->picture = $picture;
        $this->price = $price;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->categoryId,
            'picture' => $this->picture,
            'price' => $this->price,
        ];
    }
}