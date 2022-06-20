# bootstrap.php

這個 php 是被用來讀取一些一次性定義的套件或是設定。

## Logger

## Dotenv

## Twig

## CommonMark

## router

```php
<?php
session_start();
// Load our autoloader
require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

// create a log channel
$log = new Logger('Debug');
$log->pushHandler(new StreamHandler('php://stdout', Level::Warning));

// dotenv
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/public/views');
$twig = new \Twig\Environment($loader);

// markdown
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

$config = [];

$environment = new Environment($config);
$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new GithubFlavoredMarkdownExtension());

$converter = new MarkdownConverter($environment);
```