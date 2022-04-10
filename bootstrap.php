<?php
// Load our autoloader
require_once __DIR__ . '/vendor/autoload.php';

// dotenv
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv -> load();

// twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);
$twig -> addFunction(new \Twig_SimpleFunction('asset', function($asset) {
  return sprintf('./assets/%s', ltrim($asset, '/'));
}));
