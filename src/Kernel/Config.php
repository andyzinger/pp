<?php

namespace Kernel;

use Kernel\Utils\Str;

class Config
{
    private ?string $apiUrl;
    private ?string $login;
    private ?string $password;
    private ?int $transactionStartId;
    private ?string $dbHost;
    private ?string $dbPort;
    private ?string $dbName;
    private ?string $dbUserName;
    private ?string $dbPassword;

    public function __construct($config)
    {
        $this->load($config);
    }

    public function getTransactionStartId(): ?int
    {
        return $this->transactionStartId;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getApiUrl(): ?string
    {
        return $this->apiUrl;
    }

    public function getDbHost(): ?string
    {
        return $this->dbHost;
    }

    public function getDbPort(): ?string
    {
        return $this->dbPort;
    }

    public function getDbName(): ?string
    {
        return $this->dbName;
    }

    public function getDbUserName(): ?string
    {
        return $this->dbUserName;
    }

    public function getDbPassword(): ?string
    {
        return $this->dbPassword;
    }

    private function load(array $config): void
    {
        foreach ($this->getProperties() as $property) {
            $this->{$property->getName()} = $config[Str::camelToSnakeCase($property->getName())] ?? null;
        }
    }

    private function getProperties(): array
    {
        $reflection = new \ReflectionClass($this);

        return $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
    }
}