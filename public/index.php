<?php
declare (strict_types = 1);

// Load our autoloader
require_once '../bootstrap.php';

$menu = [
    '首頁' => [
        'name' => '首頁',
        'href' => '/',
    ],
    '主題' => [
        'name' => '主題館藏',
        'href' => '/collection',
    ],
    '關於' => [
        'name' => '關於',
        'href' => '/about',
    ],
    '除錯' => [
        'name' => '除錯頁面',
        'href' => '/debug',
    ],
];

use function \Util\pr;
use \Classes\Database as db;
$db = new db();

/**
 * Get method
 **/

// Create Router instance
$router = new \Bramus\Router\Router();

// 首頁
$router->get('/', function () use ($twig, $menu) {
    echo $twig->render('index.twig', [
        'session' => $_SESSION,
        'menu' => $menu
    ]);
});

// 搜尋書籍
$router->get('/discovery', function () use ($twig, $menu, $db) {
    $target = $_GET["search"];
    $results = $db->findExistBooks($target);
    echo $twig->render('discovery.twig', [
        'session' => $_SESSION,
        'menu' => $menu,
        'results' => $results
    ]);
});

// 書籍
$router->get('/discovery/book(/\d+)?', function ($bookNO = null) use ($twig, $menu, $db) {
    $book = $db->findBookById($bookNO);
    $book["Author"] = explode("|", $book["Author"]);
    echo $twig->render("book.twig", [
        'session' => $_SESSION,
        'book' => $book,
        'menu' => $menu
    ]);
});

// 關於
$router->get('/reader', function () use ($twig, $menu) {
    echo $twig->render('about.twig', [
        'session' => $_SESSION,
        'menu' => $menu
    ]);
});

// 關於
$router->get('/about', function () use ($twig, $menu) {
    echo $twig->render('about.twig', [
        'session' => $_SESSION,
        'menu' => $menu
    ]);
});

// 主題館藏
$router->get('/collection', function () use ($twig, $menu) {
    echo $twig->render('collection.twig', [
        'session' => $_SESSION,
        'menu' => $menu
    ]);
});

// 除錯
$router->get('/debug', function () use ($twig, $db, $menu) {
    echo $twig->render('debug.twig', [
        'session' => $_SESSION,
        'menu' => $menu
    ]);
    echo pr($_SESSION);
    var_dump($db->findExistRow("Reader", "Email", "asd@gmail.com"));

});

/**
 * Post method
 */
$router->post('/auth/login', function () use ($db) {
    // header('Content-Type: application/json');
    $userEmail = $_POST["userEmail"];
    $userPass = $_POST["userPass"];
    $jsonArray = array();

    if ($db->auth($userEmail, $userPass)) {
        $row = $db->findExistRow("Reader", "Email", $userEmail, true)[0];
        $user = $row["Name"];
        $jsonArray['user'] = $user;
        $jsonArray['status'] = true;

        $_SESSION['reader'] = $user;
    } else {
        $jsonArray['status'] = false;
        $jsonArray['status_text'] = "您的帳號或密碼錯誤，請重新輸入或重設密碼。";
    };
    echo json_encode($jsonArray);
});

$router->post('/auth/register', function () use ($db) {
    $jsonArray = array();

    if (isset($_POST["method"]) & $_POST["method"] == "check") {
        $result = $db->findExistRow("Reader", "Email", $_POST["mail"]);
        $jsonArray["status"] = !$result;
    } else {
        $jsonArray = $_POST;
    }
    
    echo json_encode($jsonArray);
});

$router->post('/auth/logout', function () {
    unset($_SESSION["reader"]);

    $jsonArray = array();
    $jsonArray['status'] = true;
    $jsonArray['status_text'] = "登出成功";
    echo json_encode($jsonArray);
});

// before route middleware
$router->before("GET|POST", '/reader/?.*', function () {
    if (!isset($_SESSION["reader"])) {
        header("location: /");
        exit();
    }
});


// 找不到捏 頁面
$router->set404(function () use ($twig, $menu) {
    header('HTTP/1.1 404 Not Found');
    echo $twig->render("404.twig", [
        'session' => $_SESSION,
        'menu' => $menu
    ]);
});

// 執行
$router->run();
