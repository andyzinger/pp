<?php


namespace Kernel\Services\Transactions;


class FileTransactionsService implements TransactionServiceInterface
{
    private const FILE_NAME = 'transaction.txt';
    private int $startValue;

    public function __construct(int $startValue)
    {
        $this->startValue = $startValue;
    }

    public function increment(): void
    {
        $value = $this->get();
        $value++;

        file_put_contents($this->getFilePath(), $value);
    }

    public function get(): ?int
    {
        if (!file_exists($this->getFilePath())) {
            return null;
        }

        $value = file_get_contents($this->getFilePath());

        return $value === false ? null : (int)$value;
    }

    public function init(): void
    {
        file_put_contents($this->getFilePath(), $this->startValue);
    }

    private function getFilePath()
    {
        return getRootPath(self::FILE_NAME);
    }
}