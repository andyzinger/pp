<?php


namespace Kernel\Services;


use Kernel\DTO\Product;

class ProductResponseParser
{
    public static function parse($response)
    {
        $parsedResponse = simplexml_load_string($response);

        $result = [];

        $products = (array)$parsedResponse->Products ?? [];
        $products = $products[array_key_first($products)];

        foreach ($products as $product) {
            $dto = self::createDTO($product);

            if ($dto instanceof Product) {
                $result[] = $dto;
            }
        }

        return $result;

    }

    private static function createDTO($data): ?Product
    {
        $id = (int)$data->Id;
        $name = (string)$data->Name;
        $categoryId = (int)$data->Category['id'];
        $picture = (string)$data->Picture;
        $price = (int)$data->Price;

        try {
            return new Product($id, $name, $categoryId, $picture, $price);
        } catch (\Exception $exception) {
            return null;
        }
    }
}