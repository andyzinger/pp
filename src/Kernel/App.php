<?php

namespace Kernel;

class App
{
    private Config $config;

    private static ?App $instance = null;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    public static function getInstance(): App
    {
        if (static::$instance !== null) {
            return static::$instance;
        }

        if (!file_exists(getRootPath('config.php'))) {
            throw new \Exception('Config file not found');
        }

        $config = require_once getRootPath('config.php');

        static::$instance = new static($config);

        return static::$instance;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function db(): \PDO
    {
        return new \PDO(
            "mysql:host={$this->config->getDbHost()};dbname={$this->config->getDbName()}",
//            "mysql:host=mysql;dbname=test",
            $this->config->getDbUserName(),
            $this->config->getDbPassword()
        );
    }
}