<?php

namespace Kernel\Services;


use Kernel\Services\Transactions\TransactionServiceInterface;
use Kernel\Utils\XmlEncoder;

class ApiService
{
    public const METHOD_GET_CATEGORIES = 'GetCategories';
    public const METHOD_GET_PRODUCTS = 'GetProduct';

    public const ROUTE_GET_CATEGORIES = 'v1/GetCategories/';
    public const ROUTE_GET_PRODUCTS = 'v1/GetProduct/';

    private string $login;
    private string $password;
    private string $apiUrl;
    private TransactionServiceInterface $transactionsService;

    public function __construct(
        string $apiUrl,
        string $login,
        string $password,
        TransactionServiceInterface $transactionService
    )
    {
        $this->apiUrl = $apiUrl;
        $this->login = $login;
        $this->password = $password;
        $this->transactionsService = $transactionService;
    }

    public function getCategories()
    {
        [$success, $data] = $this->executeRequest(
            self::ROUTE_GET_CATEGORIES,
            $this->getAuthRequestParams( $this->getTransactionId(), self::METHOD_GET_CATEGORIES)
        );

        $this->transactionsService->increment();

        return $data;
    }

    public function getProducts()
    {
        $auth = $this->getAuthRequestParams( $this->getTransactionId(), self::METHOD_GET_PRODUCTS);

        [$success, $data] = $this->executeRequest(
            self::ROUTE_GET_PRODUCTS,
            array_merge($auth, ['Parameters' => ''])
        );

        $this->transactionsService->increment();

        return $data;
    }

    private function executeRequest(string $route, array $requestParams): array
    {
        $url = trim($this->apiUrl, '/') . '/' . $route;
        $request = [
            'Request' => [
                'content' => $requestParams,
            ],
        ];
        $requestXml = XmlEncoder::encode($request);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,  $requestXml);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($curl);
        curl_close($curl);
        return [$data !== false, $data];
    }

    private function getAuthRequestParams(int $transaction, string $method): array
    {
       return [
            'Authentication' => [
                'content' => [
                    'Login' => $this->login,
                    'TransactionID' => $transaction,
                    'MethodName' => $method,
                    'Hash' => $this->getHash($transaction, $method),
                ]
            ]
        ];
    }

    private function getHash(int $transaction, string $method)
    {
        return md5("{$transaction}{$method}{$this->login}{$this->password}");
    }

    private function getTransactionId()
    {
        $value = $this->transactionsService->get();

        if ($value === null) {
            $this->transactionsService->init();

            return $this->transactionsService->get();
        }

        return $value;
    }
}