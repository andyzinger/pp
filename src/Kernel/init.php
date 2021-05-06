<?php

define('ROOT_PATH', dirname(__DIR__));

function getRootPath($path = null)
{
    return $path === null
        ? ROOT_PATH
        : ROOT_PATH . DIRECTORY_SEPARATOR . $path;
}

spl_autoload_register(function ($className) {
    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    include getRootPath($fileName);
});


function app(): \Kernel\App
{
    return \Kernel\App::getInstance();
}