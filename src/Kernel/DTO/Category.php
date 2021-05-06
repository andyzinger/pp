<?php

namespace Kernel\DTO;


class Category
{
    private int $id;
    private ?int $parentId;
    private string $name;
    private int $totalProducts;

    public function __construct(int $id, ?int $parentId, string $name, int $totalProducts)
    {
        $this->id = $id;
        $this->parentId = $parentId;
        $this->name = $name;
        $this->totalProducts = $totalProducts;
    }

    public function toArray()
    {
        return [
            'id'=> $this->id,
            'parent_id' => $this->parentId,
            'name' => $this->name,
            'total_products' => $this->totalProducts,
        ];
    }
}