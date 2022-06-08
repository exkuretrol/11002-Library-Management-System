<?php
session_start();
// Load our autoloader
require_once __DIR__ . '/vendor/autoload.php';

// dotenv
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv -> load();

// twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/public/views');
$twig = new \Twig\Environment($loader);
