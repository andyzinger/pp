<?php


namespace Kernel\Services;


use Kernel\DTO\Category;

class CategoriesService
{
    /**
     * @param Category[] $categories
     * @return bool
     */
    public function saveAll(array $categories): bool
    {
        $db = app()->db();
        $db->beginTransaction();

        $db->exec('DELETE FROM categories');

        foreach ($categories as $category) {
            $data = $category->toArray();
            $statement = $db->prepare('INSERT INTO categories (id, parent_id, name, total_products) VALUES (?, ?, ?, ?)');
            $statement->bindParam(1, $data['id']);
            $statement->bindParam(2, $data['parent_id']);
            $statement->bindParam(3, $data['name']);
            $statement->bindParam(4, $data['total_products']);

            $statement->execute();
        }

        return $db->commit();
    }
}