<?php
session_start();
// Load our autoloader
require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('Debug');
$log->pushHandler(new StreamHandler('php://stdout', Level::Warning));

// dotenv
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv -> safeLoad();

// twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/public/views');
$twig = new \Twig\Environment($loader);
