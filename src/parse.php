<?php

use Kernel\Services\ApiService;
use Kernel\Services\CategoriesResponseParser;
use Kernel\Services\CategoriesService;
use Kernel\Services\ProductResponseParser;
use Kernel\Services\ProductsService;
use Kernel\Services\Transactions\FileTransactionsService;

require_once __DIR__ . '/Kernel/init.php';

$config = app()->getConfig();

$apiService = new ApiService(
    $config->getApiUrl(),
    $config->getLogin(),
    $config->getPassword(),
    new FileTransactionsService($config->getTransactionStartId())
);

$categoriesResponse = $apiService->getCategories();
$categories = CategoriesResponseParser::parse($categoriesResponse);
$categoriesService = new CategoriesService();
$categoriesService->saveAll($categories);

$productsResponse = $apiService->getProducts();
$products = ProductResponseParser::parse($productsResponse);
$productsService = new ProductsService();
$saved = $productsService->saveAll($products);

//todo: добавить логирование в случае ошибки при сохранении данных