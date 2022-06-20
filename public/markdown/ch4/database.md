# Database.php

這個頁面我會介紹我們為資料庫寫的連接資料庫類別（[Classes](https://zh.wikipedia.org/wiki/%E7%B1%BB_(%E8%AE%A1%E7%AE%97%E6%9C%BA%E7%A7%91%E6%AD%A6))），這個類別被廣泛地用在`index.php`中，只要有碰到關於資料庫的動作都會用到它。

## 概述

其中主要個幾個功能如下：
- `getConnection()`：直接與資料庫連線，若連線未建立，則連線至資料庫；若無，則新增一個連線。
- `execute()`：直接執行指定的 sql 語法。
- `findExistRow()`：根據給予的表格名稱、欄位名稱、比較的值找出多筆資料。
- `insertOneRow()`：插入一行新資料列。
    - `concatArr()`：將陣列組合成 'item' 或是 `item`。
- `register()`：註冊一名讀者。
- `findExistBooks()`：根據書籍名稱找出一本書。
- `findBookById()`：根據書籍編號找出一本書。
- `auth()`：認證帳號。

## 檔案開頭
```php
<?php
// 設定 php 為嚴格的限制資料的類型
declare (strict_types = 1);
namespace Classes;
class Database
{
    //[...]
}
```

這段語法會嚴格的限制資料的類型，像是指定方法（`method`）回傳結果時，如果出現跟預先指定的資料型態不一樣的話，就會出現錯誤。

## getConnection

這是連接資料庫/獲取連線的程式碼：

```php
private $conn;
/**
 * 直接與資料庫連線，若連線未建立，則連線至資料庫
 * ；若無，則新增一個連線。
 *
 * @return \PDO
 */
public function getConnection(): \PDO
{
    // 如果連線不存在
    if (!$this->conn) {
        try {
            $this->conn = new \PDO(
                "mysql:host={$_ENV['host']}:{$_ENV['port']};dbname={$_ENV['name']}",
                $_ENV['user'],
                $_ENV['pass']
            );
            // $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Database error";
        }
    }
    return $this->conn;
}
```

首先`private`字樣是物件導向（Object-Oriented）裡面一個被稱作修飾子（[Modifiers](https://www.w3schools.com/php/php_oop_access_modifiers.asp)）的東西。簡單來說這裡就是保護我的連線不會被直接存取，要經過Database這個物件操作它。

而`try...catch`語法會先執行過try裡面的語句，當try裡面的語句出現錯誤時，錯誤會被catch捕捉到例外之中，並執行catch中的程式碼。

在接下來就是[`PDO`物件](https://www.php.net/manual/en/book.pdo.php)在新增的時候，會做連線。其中`$_ENV`變數是利用系統環境中的變數來抓到對應的變數。像是這裡的host變數在系統中對應的值就是資料庫的連結網址`hub.kuaz.info`。

這裡的`$this`是指`Database`物件本身，只能在方法（`method`）中使用。

## execute

這個與法會直接執行輸入的SQL語句，如果`simple`設為`true`時會只取回欄位名稱為鍵值的陣列。

參數說明：
- `$sql`：sql片語
- `$simple`：是否以較簡單的方式取回陣列

```php
/**
 * 直接執行指定的 sql 語法 
 *
 * @param [type] $sql sql 語法
 * @param boolean $simple 使否用 FETCH_ASSOC 取回資料
 * @return mixed
 */
public function execute($sql, $simple = false): mixed
{
    if (!$this->conn) {
        $this->getConnection();
    }
    try {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $simple ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "<p>" . $exception->getMessage() . "</p>";
        exit;
    }
}
```

這裡有個特殊的片語是`<條件> ? <當條件為真> : <當條件為假>`。這個片語被稱作是三元運算子（[Ternary Operation](https://www.w3schools.com/php/php_operators.asp)）這個片語簡單的將`if...else...`濃縮在僅僅一行程式碼中，使用起來非常方便。

## findExistRow

這個方法被我用來找出資料庫中的資料，甚至被我其他的方法引用。

參數說明：
- `$table`：資料表名稱
- `$attr`：目標欄位名稱
- `$target`：目標值
- `$returnResult`：是否返回結果，如果設為否，則回傳 true/false；如果設為是，則回傳結果。
- `$partialMatch`：是否部分匹配

```php
    /**
     * 找出一行/多行資料
     *
     * @param [type] $table             資料表
     * @param [type] $attr              欄位名稱
     * @param [type] $target            where 後面放的字
     * @param boolean $returnResult     是否返回結果，如果設為否，則回傳 true/false；
     *                                  如果設為是，則回傳結果。
     * @param boolean $partialMatch     是否部分匹配
     * @return mixed
     */
    public function findExistRow($table, $attr, $target, $returnResult = false, $partialMatch = false): mixed
    {
        if (!$this->conn) {
            $this->getConnection();
        }
        try {
            if (!$partialMatch)
                $sql = "select * from `$table` where `$attr` = :target;";
            else
                $sql = "select * from `$table` where `$attr` like concat('%', :target, '%');";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("target", $target, \PDO::PARAM_STR);
            $stmt->execute();
            if ($returnResult) $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (!$returnResult) $result = $stmt->rowCount() !==0 ? true : false;
            return $result;
        } catch (PDOException $e) {
            echo "<p>" . $e->getMessage() . "</p>";
            exit;
        }
    }
```

當部分匹配為假，`$sql`會等於上面的語句。

```php
if (!$partialMatch)
    $sql = "select * from `$table` where `$attr` = :target;";
else
    $sql = "select * from `$table` where `$attr` like concat('%', :target, '%');";
```

而第一句語句，在字串中放置變數的方法也是一個特殊的用法，要用這個用法有兩個前提：

1. 字串的外圍要用`"`包起
2. 所插入的變數名稱要用`` ` ``包起來

## insertOneRow

插入一行新的資料列

參數說明：
- `$table`：資料表名稱
- `$insertArr`：插入資料的陣列
- `$colArr`：被用來匹配的欄位名稱陣列

```php
/**
 * 插入一行新資料列
 *
 * @param [type] $table         資料表名稱
 * @param [type] $insertArr     插入資料的陣列
 * @param [type] $colArr        插入資料的欄位名稱
 * @return void
 */
public function insertOneRow($table, $insertArr, $colArr) {
    if (!$this->conn) {
        $this->getConnection();
    }
    try {
        $colStr = $this->concatArr($colArr);
        $insertStr = $this->concatArr($insertArr, true);
        $sql = "insert into `{$table}` (${colStr}) values (${insertStr})";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
    }
    catch (PDOException $e) {
        echo "<p>" . $e->getMessage() . "</p>";
        exit;
    }
}
```

### concatArr

因為SQL語法`insert into`片語的欄位名稱不能用字串的方式放進去，需要在外面加上`` ` ``，所以特地多寫了組合字串的方法供`insertOneRow()`使用。

```php
    /**
     * 將陣列組合成 'item' 或是 `item`
     *
     * @param [type] $Arr       輸入陣列
     * @param boolean $isStr    是不是字串
     * @return string           回傳字串
     */
    public function concatArr($Arr, $isStr = false): string
    {
        $str = "";
        $symbol = "`";
        if ($isStr) $symbol = "'";
        foreach($Arr as $item) $str .= "{$symbol}{$item}${symbol}, ";
        $str = substr($str, 0, -2);
        return $str;
    }
```

這裡的`substr($str, 0, -2)`是將組合好後最多多餘的倒數兩個字元`, `拿掉。


## register

這個方法會註冊一名讀者。

參數說明：
- `$insertArr`：輸入一串含有鍵值為`email`, `pass`, `name`, `gender`, `birthdate`與`phone`的陣列。

```php
/**
 * 註冊一名讀者
 *
 * @param [type] $insertArr 註冊資料陣列
 * @return bool             回傳 true/false
 */
public function register($insertArr): bool
{
    $result = true;
    $colArr = array("Email", "Password", "Name", "Gender", "BirthDate", "PhoneNum");
    $arrangedArr = array(
        $insertArr["email"],
        $insertArr["pass"],
        $insertArr["name"],
        $insertArr["gender"],
        $insertArr["birthdate"],
        $insertArr["phone"],
    );
    if (!$this->findExistRow("Reader", "Email", $insertArr["email"])) {
        $this->insertOneRow("Reader", $arrangedArr, $colArr);
    } else {
        $result = false;
    }

    return $result;
}
```

## findExistBooks

根據書名找出一本書

參數說明
- `$bookName`：書名

```php
/**
 * 根據書名找出一本書
 *
 * @param [type] $bookName  書名
 * @return array            書的資料陣列
 */
public function findExistBooks($bookName): array
{
    return $this->findExistRow("Book", "Title", $bookName, true, true); 
}
```

## findExistBooks

根據書籍編號找出一本書

參數說明
- `$bookName`：書名

```php
/**
 * 根據書的ID找出一本書
 *
 * @param [type] $bookNo    書的ID
 * @return array            書的資料陣列
 */
public function findBookById($bookNo): array
{
    return $this->findExistRow("Book", "BookNumber", $bookNo, true)[0]; 
}
```

## auth

根據輸入的電子信箱與密碼認證。

參數說明：
- `$userEmail`：電子信箱
- `userpass`：密碼

```php
/**
 * 認證帳號
 *
 * @param [type] $userEmail 使用者 Email
 * @param [type] $userPass  使用者密碼
 * @return bool
 */
public function auth($userEmail, $userPass): bool
{
    $row = $this->findExistRow("Reader", "Email", $userEmail, true);

    if (count($row) == 0) {
        $auth = false;
        return $auth;
    }

    $row = $row[0];
    $auth = ($userEmail == $row["Email"] & $userPass == $row["Password"]) ? true : false;
    return $auth;
}
```