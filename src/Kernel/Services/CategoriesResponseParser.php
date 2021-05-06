<?php


namespace Kernel\Services;


use Kernel\DTO\Category;

class CategoriesResponseParser
{
    /**
     * @param $response
     * @return Category[]
     */
    public static function parse($response)
    {
        $parsedResponse = simplexml_load_string($response);

        $result = [];

        $categories = (array)$parsedResponse->Categories ?? [];
        $categories = $categories[array_key_first($categories)];

        foreach ($categories as $category) {
            $dto = self::createDTO($category);

            if ($dto instanceof Category) {
                $result[] = $dto;
            }
        }

        return $result;
    }

    private static function createDTO($data): ?Category
    {
        $id = (int)$data['id'];
        $parentId = (int)$data['parentId'];
        $name = (string)$data->name;
        $totalProducts = (int)$data->totalProducts;

        try {
            return new Category($id, $parentId, $name, $totalProducts);
        } catch (\Exception $exception) {
            return null;
        }
    }
}