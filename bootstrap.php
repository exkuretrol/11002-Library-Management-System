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

list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));