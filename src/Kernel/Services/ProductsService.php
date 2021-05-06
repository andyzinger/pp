<?php


namespace Kernel\Services;


use Kernel\DTO\Product;

class ProductsService
{
    /**
     * @param Product[] $products
     * @return bool
     */
    public function saveAll(array $products): bool
    {
        $db = app()->db();
        $db->beginTransaction();

        $db->exec('DELETE FROM products');

        foreach ($products as $product) {
            $data = $product->toArray();
            $statement = $db->prepare('INSERT INTO products (id, name, category_id, picture, price) VALUES (?, ?, ?, ?, ?)');
            $statement->bindParam(1, $data['id']);
            $statement->bindParam(2, $data['name']);
            $statement->bindParam(3, $data['category_id']);
            $statement->bindParam(4, $data['picture']);
            $statement->bindParam(5, $data['price']);

            $statement->execute();
        }

        return $db->commit();
    }
}