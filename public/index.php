<?php
declare (strict_types = 1);
require_once '../bootstrap.php';

// Create a product list
$products = [
    [
        'name' => 'Notebook',
        'description' => 'Core i7',
        'value' => 800.00,
        'date_register' => '2017-06-22',
    ],
    [
        'name' => 'Mouse',
        'description' => 'Razer',
        'value' => 125.00,
        'date_register' => '2017-10-25',
    ],
    [
        'name' => 'Keyboard',
        'description' => 'Mechanical Keyboard',
        'value' => 250.00,
        'date_register' => '2017-06-23',
    ],
];

$menu = [
    [
        'name' => '首頁',
        'href' => '/',
    ],
    [
        'name' => '關於',
        'href' => '/about',
    ],
    [
        'name' => '企劃書',
        'href' => '/report',
    ],
    [
        'name' => 'debug',
        'href' => '/debug',
    ],
];

use function \Util\pr;

// Create Router instance
$router = new \Bramus\Router\Router();

// Define routes
$router->get('/', function () use ($twig, $products, $menu) {
    echo $twig->render('index.twig', ['products' => $products, 'menu' => $menu]);
});

// use \Library\World as World;
use \Library\Database as db;

$db = new db();
$conn = $db->getConnection();

$router->get('/about', function () use ($twig, $menu) {
    echo $twig->render('about.twig', ['menu' => $menu]);
});

$router->get('/report', function () use ($twig, $menu) {
    // echo $twig->render('report.twig', ['menu' => $menu]);
    echo $twig->render('report.html');
});

// 除錯頁面
$router->get('/debug', function () use ($twig, $menu, $conn) {
    echo $twig->render('debug.twig', ['menu' => $menu]);
    // $limit = 5;
    $stmt = $conn->prepare("show variables like '%connection%'");
    // $stmt->bindParam(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    pr($stmt->fetchAll());
});

// 404 page
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo "找不到捏";
});

// Run it!
$router->run();
