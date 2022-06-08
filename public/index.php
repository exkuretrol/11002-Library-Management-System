<?php
declare (strict_types = 1);

// Load our autoloader
require_once('../bootstrap.php');

function nav()
{
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
        '登入' => [
            'name' => '讀者登入',
            'href' => '/login',
        ],
    ];
    if (isset($_SESSION["user"])) {
        unset($menu["登入"]);
    }

    return $menu;
}

use function \Util\pr;

/**
 * Get method
 **/
// use \Library\World as World;
use \Classes\Database as db;

$db = new db();

// Create Router instance
$router = new \Bramus\Router\Router();

// 首頁
$router->get('/', function () use ($twig) {
    echo $twig->render('index.twig', [
        'menu' => nav(),
        'session' => $_SESSION,
    ]);
});

// 讀者登入
$router->get('/login', function () use ($twig) {
    echo $twig->render('login.twig', ['menu' => nav()]);
});

// 關於頁面
$router->get('/about', function () use ($twig) {
    echo $twig->render('about.twig', ['menu' => nav()]);
});

// 除錯頁面
$router->get('/debug', function () use ($twig, $db) {
    echo $twig->render('debug.twig', ['menu' => nav()]);
    pr($db->execute("select * from Moderator", true));
    // var_dump($db->findExistRowNum("Moderator", "Email", "0870875@me.mcu.edu.tw"));
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

        $_SESSION['user'] = $user;
    } else {
        $jsonArray['status'] = false;
        $jsonArray['status_text'] = "您的帳號或密碼錯誤，請重新輸入或重設密碼。";
    };
    echo json_encode($jsonArray);
});

// 找不到捏 頁面
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo "找不到捏";
});

// 執行
$router->run();
