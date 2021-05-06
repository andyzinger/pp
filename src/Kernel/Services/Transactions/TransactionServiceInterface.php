<?php


namespace Kernel\Services\Transactions;


interface TransactionServiceInterface
{
    public function increment(): void;
    public function get(): ?int;
    public function init(): void;
}