<?php
declare (strict_types = 1);

// Load our autoloader
require_once '../bootstrap.php';

$menu = [
    '首頁' => [
        'name' => '首頁',
        'href' => '/',
        'disabled' => false,
    ],
    '主題' => [
        'name' => '主題館藏',
        'href' => '/collection',
        'disabled' => true,
    ],
    '推薦書籍' => [
        'name' => '推薦書籍',
        'href' => '/recommend',
        'disabled' => true,
    ],
    '網站說明' => [
        'name' => '網站說明',
        'href' => '/report',
        'disabled' => false,
    ],
    '除錯' => [
        'name' => '除錯頁面',
        'href' => '/debug',
        'disabled' => true,
    ],
    '關於' => [
        'name' => '關於',
        'href' => '/about',
        'disabled' => false,
    ],
];

$report_pages = [
    '主題動機' => [
        'name' => '主題動機',
        'href' => '/ch1/theme',
        'nested' => false,
    ],
    '網站架構' => [
        'name' => '網站架構',
        'href' => '/ch2/site-map',
        'nested' => false,
    ],
    '資料表及內容' => [
        'name' => '資料表及內容',
        'href' => '/ch3/tables-explanation',
        'nested' => false,
    ],
    '網頁分工及說明' => [
        'name' => '網頁分工及說明',
        'href' => null,
        'nested' => true,
        'pages' => [
            'bootstrap' => [
                'name' => 'bootstrap.php',
                'href' => '/ch4/bootstrap',
                'nested' => false,
            ],
            'database' => [
                'name' => 'Database.php',
                'href' => '/ch4/database',
                'nested' => false,
            ],
            'helper' => [
                'name' => 'helper.php',
                'href' => '/ch4/helper',
                'nested' => false,
            ],
            'index' => [
                'name' => 'index.php',
                'href' => '/ch4/index',
                'nested' => false,
            ],
            'divider' => [
                'name' => 'divider',
                'href' => null,
                'nested' => false,
            ],
            'index.twig' => [
                'name' => 'index.twig',
                'href' => '/ch4/index_twig',
                'nested' => false,
            ],
        ],
    ],
    '分工表' => [
        'name' => '分工表',
        'href' => '/ch5/work-division',
        'nested' => false,
    ],
    'divider' => [
        'name' => 'divider',
        'href' => null,
        'nested' => false,
    ],
    '關於我們' => [
        'name' => '關於我們',
        'href' => '/about-us',
        'nested' => false,
    ],
];

function active_pages(array $pages, String $str): array
{
    function check_active(array $v, String $str): bool
    {
        return ($v["href"] == $str) ? true : false;
    }

    foreach ($pages as $k => $v) {
        if (!$v["nested"]) {
            if (check_active($v, $str)) {
                $pages[$k]["active"] = true;
                return $pages;
            }
        }
        if ($v["nested"]) {
            foreach ($v["pages"] as $kk => $vv) {
                if (check_active($vv, $str)) {
                    $pages[$k]["pages"][$kk]["active"] = true;
                    $pages[$k]["active"] = true;
                    return $pages;
                }
            }
        }
    }
}

use function \Util\pr;
use \Classes\Database as db;
$db = new db();

/**
 * Get method
 **/

// Create Router instance
$router = new \Bramus\Router\Router();

// 首頁
$router->get('/', function () use ($twig, $menu, $db) {
    $maintain = $db->execute("select * from Post where Type = 0 and (`DueDate` >= CURRENT_DATE or isnull(`DueDate`)) order by PublishDate");
    $news = $db->execute("select * from Post where Type = 1 and (`DueDate` >= CURRENT_DATE or isnull(`DueDate`)) order by PublishDate");
    $updates = $db->execute("select * from Post where Type = 9 and (`DueDate` >= CURRENT_DATE or isnull(`DueDate`)) order by PublishDate");
    echo $twig->render('index.twig', [
        'session' => $_SESSION,
        'menu' => $menu,
        'maintain' => $maintain,
        'news' => $news,
        'updates' => $updates,
    ]);
});

// 搜尋書籍
$router->get('/discovery', function () use ($twig, $menu, $db) {
    $target = $_GET["search"];
    if ($target !== "") {
        $results = $db->findExistBooks($target);
    } else {
        $results = $db->execute("select * from Book");
    }

    echo $twig->render('discovery.twig', [
        'session' => $_SESSION,
        'menu' => $menu,
        'results' => $results,
    ]);
});

// 書籍
$router->get('/discovery/book(/\d+)?', function ($bookNO = null) use ($twig, $menu, $db) {
    $book = $db->findBookById($bookNO);
    $book["Author"] = explode("|", $book["Author"]);
    echo $twig->render("book.twig", [
        'session' => $_SESSION,
        'book' => $book,
        'menu' => $menu,
    ]);
});

// 讀者服務
$router->get('/reader', function () use ($twig, $menu, $db) {
    $email = $_SESSION["email"];
    $readerNO = $_SESSION["readerNO"];
    $profile = $db->findExistRow("Reader", "Email", $email, true)[0];
    $sql = <<< EOF
    SELECT
        ID,
        Title,
        Date,
        Status
    FROM
        Reserved
    left join
        Book
    on
        Reserved.R_BookNumber = Book.BookNumber
    WHERE
        R_ReaderNumber = $readerNO and
        Status != 2
    EOF;

    $reserved = $db->execute($sql, true);

    $sql = <<< EOF
    select
        ID,
        Title,
        BorrowDate,
        DueDate
    from
        CirculatedCopy
    left join
        Copy
    on
        CirculatedCopy.CopyNumber = Copy.CopyNumber
    left join
        Book
    on
        Copy.BookNumber = Book.BookNumber
    where
        ReaderNumber = $readerNO AND
        ReturnDate is null
    EOF;

    $borrowed = $db->execute($sql);
    unset($profile["ReaderNumber"]);
    unset($profile["Password"]);
    echo $twig->render('reader.twig', [
        'session' => $_SESSION,
        'menu' => $menu,
        'infos' => $profile,
        'reserved' => $reserved,
        'borrowed' => $borrowed,
    ]);
});

// 管理頁面
$router->get('/admin', function () use ($router, $twig, $menu, $db) {
    $validated = true;
    if (isset($_SERVER['PHP_AUTH_USER'])) {
        $user = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];
        $row = $db->findExistRow("Moderator", "Email", $user, true);
        if (count($row) == 0) {
            $validated = false;
        } else {
            if ($row[0]["Password"] !== $pass) {
                $validated = false;
            }
        }
    }

    if (!$validated | !isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo $twig->render("404.twig", [
            'session' => $_SESSION,
            'menu' => $menu,
        ]);
    } else {
        $_SESSION['admin'] = $user;
        // TODO: 把 sql 語句變成方法
        $sql = <<< EOF
        select
            ID,
            R_ReaderNumber as ReaderNumber,
            Reader.Name,
            BookNumber,
            Title,
            Date
        from
            Reserved
        left join
            Book
        on
            Reserved.R_BookNumber = Book.BookNumber
        left join
            Reader
        on
            Reserved.R_ReaderNumber = Reader.ReaderNumber
        where
            Reserved.Status = 0;
        EOF;
        $reserved = $db->execute($sql, true);

        $sql = <<< EOF
        select
            ID,
            R_ReaderNumber as ReaderNumber,
            Reader.Name,
            BookNumber,
            Title,
            Date
        from
            Reserved
        left join
            Book
        on
            Reserved.R_BookNumber = Book.BookNumber
        left join
            Reader
        on
            Reserved.R_ReaderNumber = Reader.ReaderNumber
        where
            Reserved.Status = 1;
        EOF;
        $borrow_reserved = $db->execute($sql, true);

        echo $twig->render("admin.twig", [
            'session' => $_SESSION,
            'menu' => $menu,
            'reserved' => $reserved,
            'borrow_reserved' => $borrow_reserved,
        ]);
    }
});

// 關於
$router->get('/about', function () use ($twig, $menu) {
    echo $twig->render('about.twig', [
        'session' => $_SESSION,
        'menu' => $menu,
    ]);
});

// 主題館藏
$router->get('/collection', function () use ($twig, $menu) {
    echo $twig->render('collection.twig', [
        'session' => $_SESSION,
        'menu' => $menu,
    ]);
});

// 書籍推薦
$router->get('/recommend', function () use ($twig, $menu) {
    echo $twig->render('recommend.twig', [
        'session' => $_SESSION,
        'menu' => $menu,
    ]);
});

// 除錯
$router->get('/debug', function () use ($twig, $db, $menu) {
    $unixTime = time();
    $timeZone = new DateTimeZone('Asia/Taipei');
    $time = new DateTime();
    $time->settimestamp($unixTime)->setTimezone($timeZone);
    $now = $time->format('Y/m/d H:i:s');
    $interval = DateInterval::createFromDateString("14 day");
    $dueDate = $time->add($interval)->format('Y/m/d H:i:s');

    $NO = 4;
    $sql = <<< EOF
    select
        *
    from
        Copy
    where
        BookNumber = $NO and
        Type != 1
    limit 1
    EOF;

    $res = $db->execute($sql, $simple = true);

    echo $twig->render('debug.twig', [
        'session' => $_SESSION,
        'menu' => $menu,
        'debug' => array(
            'session' => pr($_SESSION),
            'cookie' => pr($_COOKIE),
            'res' => pr($res),
            'now' => $now,
            'due' => $dueDate,
        ),
    ]);

});

// 網站說明
$router->get('/report', function () {
    header("location: /report/ch1/theme");
    exit;
});

$router->get('/report/{section}(/\d+)?', function ($section, $subsection = null) use ($twig, $menu, $report_pages, $converter) {
    if ($subsection == null) {
        $path = "/{$section}";
    } else {
        $path = "/{$section}/{$subsection}";
    }
    $md_path  = "./markdown" . $path . ".md";
    $file = fopen($md_path, "r");
    $md = fread($file, filesize($md_path));
    $markdown = $converter->convert($md);
    fclose($file);
    echo $twig->render(
        'report.twig', [
            'menu' => $menu,
            'session' => $_SESSION,
            'pages' => active_pages($report_pages, $path),
            'markdown' => $markdown,
        ]
    );
});

/**
 * Post method
 */
$router->post('/auth/login', function () use ($db, $log) {
    // header('Content-Type: application/json');
    $log->warning(print_r($_POST, true));
    $userEmail = $_POST["userEmail"];
    $userPass = $_POST["userPass"];
    $jsonArray = array();
    $arr_cookie_options = array(
        'expires' => time() + 60 * 60 * 24 * 30,
        'path' => '/',
        'domain' => '.example.com', // leading dot for compatibility or use subdomain
        'secure' => true, // or false
        'httponly' => true, // or false
        'samesite' => 'None', // None || Lax  || Strict
    );

    if ($db->auth($userEmail, $userPass)) {
        $row = $db->findExistRow("Reader", "Email", $userEmail, true)[0];
        $user = $row["Name"];
        $NO = $row["ReaderNumber"];
        $jsonArray['user'] = $user;
        $jsonArray['status'] = true;
        $_SESSION['reader'] = $user;
        $_SESSION['readerNO'] = $NO;
        $_SESSION['email'] = $userEmail;
    } else {
        $jsonArray['status'] = false;
        $jsonArray['status_text'] = "您的帳號或密碼錯誤，請重新輸入或重設密碼。";
    };
    echo json_encode($jsonArray);
});

$router->post('/auth/register', function () use ($db, $log) {
    $jsonArray = array();

    if (isset($_POST["method"])&$_POST["method"] == "check") {
        $result = $db->findExistRow("Reader", "Email", $_POST["mail"]);
        $jsonArray["status"] = !$result;
    } else {
        $log->warning(print_r($_POST, true));
        $res = $db->register($_POST);
        $data = array(
            'secret' => "0x420D379efAd8217b763b4236A6dd9A494aE8E310",
            'response' => $_POST['h-captcha-response'],
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        $responseData = json_decode($response);
        if ($responseData->success) {
            $jsonArray["status"] = $res;
            $jsonArray["status_text"] = $res ? "註冊成功，請使用註冊的信箱及密碼登入。" : "註冊失敗";
        } else {
            $jsonArray["status"] = false;
            $jsonArray["status_text"] = "驗證失敗";
        }

    }

    echo json_encode($jsonArray);
});

$router->post('/reader/reserve', function () use ($db, $log) {
    if (!isset($_SESSION["readerNO"])) {
        $jsonArray["status"] = false;
        $jsonArray["status_text"] = "登入後，才可以使用本服務";
        echo json_encode($jsonArray);
        exit;
    }

    $borrower = $_SESSION['readerNO'];
    $bookNumber = $_POST["BookNumber"];

    // setup timestamp
    $unixTime = time();
    $timeZone = new DateTimeZone('Asia/Taipei');
    $time = new DateTime();
    $time->settimestamp($unixTime)->setTimezone($timeZone);
    $formattedTime = $time->format('Y/m/d H:i:s');
    $now = $time->format('Y/m/d H:i:s');

    $sql = <<< EOF
    select
        *
    from
        Copy
    where
        BookNumber = $bookNumber and
        Type = 0
    limit 1
    EOF;
    $res = $db->execute($sql);

    if (count($res) > 0) {
        $sql = <<< EOF
        insert into Reserved
            (
                R_ReaderNumber,
                R_BookNumber,
                Date,
                Status
            )
        values (
            $borrower,
            $bookNumber,
            "$now",
            0
        )
        EOF;

        $db->execute($sql);
        $targetCopyNumber = $res[0]["CopyNumber"];

        $sql = <<<EOF
        update
            Copy
        set
            Type = 2
        where
            CopyNumber = $targetCopyNumber
        EOF;

        $db->execute($sql);

        $jsonArray["status"] = true;
        $jsonArray["status_text"] = "預借成功，請撥空至一樓流通櫃台取書。";
    } else {
        $jsonArray["status"] = false;
        $jsonArray["status_text"] = "很抱歉，目前沒有多餘的書籍副本可供外借。";
    }

    echo json_encode($jsonArray);
});

$router->post('/auth/logout', function () {
    unset($_SESSION["reader"]);
    unset($_SESSION["readerNO"]);
    unset($_SESSION['email']);

    $jsonArray = array();
    $jsonArray['status'] = true;
    $jsonArray['status_text'] = "登出成功";
    echo json_encode($jsonArray);
});

$router->post('/admin/borrow', function () use ($db) {
    // setup timestamp
    $unixTime = time();
    $timeZone = new DateTimeZone('Asia/Taipei');
    $time = new DateTime();
    $time->settimestamp($unixTime)->setTimezone($timeZone);
    $formattedTime = $time->format('Y/m/d H:i:s');
    $now = $time->format('Y/m/d H:i:s');
    $interval = DateInterval::createFromDateString("14 day");
    $dueDate = $time->add($interval)->format('Y/m/d H:i:s');

    $jsonArray = array();
    if ($_POST["method"] == "reserve") {
        $IDs = $_POST["ID"];
        foreach ($IDs as $ID) {

            $sql = <<< EOF
            select
                *
            from
                Reserved
            where ID = $ID
            EOF;

            $res = $db->execute($sql, true)[0];
            $readerNO = $res["R_ReaderNumber"];
            $bookNO = $res["R_BookNumber"];

            $sql = <<< EOF
            select
                *
            from
                Copy
            where
                BookNumber = $bookNO and
                Type != 1
            limit 1
            EOF;

            $res = $db->execute($sql, true)[0];

            $insertArr = array(
                $readerNO,
                $res["CopyNumber"],
                $now,
                $dueDate,
            );

            $colArr = array(
                "ReaderNumber",
                "CopyNumber",
                "BorrowDate",
                "DueDate",
            );

            $db->insertOneRow("CirculatedCopy", $insertArr, $colArr);

            $reserveTargetCopyNO = $insertArr[1];

            // 寫入 Copy
            $sql = <<< EOF
            update
                Copy
            set
                Type = 1
            where
                CopyNumber = $reserveTargetCopyNO
            EOF;
            $db->execute($sql);

            // 更新 Reserved
            $sql = <<< EOF
            update
                Reserved
            set
                Status = 2
            where
                ID = $ID
            EOF;
            $db->execute($sql);
        }
        $jsonArray["status"] = true;
        $jsonArray["status_text"] = "輸入成功";
    }

    if ($_POST["method"] == "borrow") {
        $readerNO = $_POST["readerNO"];
        $bookNO = $_POST["bookNO"];
        $interval = $_POST["interval"];

        $interval = DateInterval::createFromDateString("${interval} day");
        $dueDate = $time->add($interval)->format('Y/m/d H:i:s');

        $sql = <<< EOF
        select
            *
        from
            Copy
        where
            BookNumber = $bookNO and
            Type != 1
        limit 1
        EOF;

        $res = $db->execute($sql, true);
        if (count($res) > 0) {
            $res = $res[0];

            $insertArr = array(
                $readerNO,
                $res["CopyNumber"],
                $now,
                $dueDate,
            );

            $colArr = array(
                "ReaderNumber",
                "CopyNumber",
                "BorrowDate",
                "DueDate",
            );

            $db->insertOneRow("CirculatedCopy", $insertArr, $colArr);

            $reserveTargetCopyNO = $insertArr[1];

            // 寫入 Copy
            $sql = <<< EOF
            update
                Copy
            set
                Type = 1
            where
                CopyNumber = $reserveTargetCopyNO
            EOF;
            $db->execute($sql);

            $jsonArray['status'] = true;
            $jsonArray['status_text'] = "借閱成功";
        } else {
            $jsonArray['status'] = false;
            $jsonArray['status_text'] = "沒有多餘的書籍副本可借或無此書籍";
        }

    }

    echo json_encode($jsonArray);

});

$router->post('/admin/reserve', function () use ($db) {
    $IDs = $_POST["ID"];
    if ($_POST["method"] == "reserve") {
        foreach ($IDs as $ID) {
            $sql = <<< EOF
            update
                Reserved
            set
                Status = 1
            where
                ID = $ID
            EOF;
            $db->execute($sql);
        }
    }
    $jsonArray = array();
    $jsonArray["status"] = true;
    $jsonArray["status_text"] = "更新成功";
    echo json_encode($jsonArray);
});

$router->post('/admin/return', function () use ($db) {
    $jsonArray = array();
    if ($_POST["method"] == "findRecords") {
        $readerNO = $_POST["readerNO"];
        $sql = <<< EOF
        select
            ID,
            Book.BookNumber,
            Title,
            FineAmount
        from
            CirculatedCopy
        left join
            Copy
        on
            CirculatedCopy.CopyNumber = Copy.CopyNumber
        left join
            Book
        on
            Copy.BookNumber = Book.BookNumber
        where
            ReaderNumber = $readerNO and
            ReturnDate is null
        EOF;
        $res = $db->execute($sql, true);
        if (count($res) > 0) {
            $jsonArray = $res;
            $jsonArray["status"] = true;
        } else {
            $jsonArray["status"] = false;
            $jsonArray["status_text"] = "查無借閱書籍";
        }
    }
    // setup timestamp
    $unixTime = time();
    $timeZone = new DateTimeZone('Asia/Taipei');
    $time = new DateTime();
    $time->settimestamp($unixTime)->setTimezone($timeZone);
    $formattedTime = $time->format('Y/m/d H:i:s');
    $now = $formattedTime;

    if ($_POST["method"] == "return") {
        $IDs = $_POST["ID"];
        foreach ($IDs as $ID) {

            // find CopyNumber
            $sql = <<< EOF
            select
                CopyNumber
            from
                CirculatedCopy
            where
                ID = $ID
            limit
                1
            EOF;
            $copyNO = $db->execute($sql, true)[0]["CopyNumber"];

            // update CirculatedCopy
            $sql = <<< EOF
            update
                CirculatedCopy
            set
                ReturnDate = "$formattedTime"
            where
                ID = $ID
            EOF;
            $db->execute($sql);

            // update Copy Type
            $sql = <<< EOF
            update
                Copy
            set
                Type = 0
            where
                CopyNumber = $copyNO
            EOF;
            $db->execute($sql);

            $jsonArray["status"] = true;
            $jsonArray["status_text"] = "成功歸還";
        }
        $jsonArray = $_POST;
    }
    echo json_encode($jsonArray);
});

/**
 * Before Middleware
 */
$router->before("GET", '/reader/?.*', function () {
    if (!isset($_SESSION["reader"])) {
        header("location: /");
        exit();
    }
});

$router->before("GET|POST", '/recommend/?.*', function () use ($twig) {
    if (!isset($_SESSION["reader"])) {
        header("location: /");
        exit();
    }
});

$router->before("POST", '/admin/?.*', function () {
    if (!isset($_SESSION["admin"])) {
        header("location: /");
        exit();
    }
});

// 找不到捏 頁面
$router->set404(function () use ($twig, $menu) {
    header('HTTP/1.1 404 Not Found');
    echo $twig->render("404.twig", [
        'session' => $_SESSION,
        'menu' => $menu,
    ]);
});

// 執行
$router->run();
