<?php
declare (strict_types = 1);
namespace Classes;

class Database
{
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
                $this->conn->exec("set names utf8");
            } catch (PDOException $exception) {
                echo "Database error";
            }
        }
        return $this->conn;
    }

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

    /**
     * 找出一行資料
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

    /**
     * 插入一行新資料列
     *
     * @param [type] $table         資料表名稱
     * @param [type] $insertArr     插入資料的串列
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

    /**
     * 將向量組合成 'item' 或是 `item`
     *
     * @param [type] $Arr       輸入向量
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

    /**
     * 註冊一名讀者
     *
     * @param [type] $insertArr 註冊資料向量
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

    /**
     * 根據書名找出一本書
     *
     * @param [type] $bookName  書名
     * @return array            書的資料向量
     */
    public function findExistBooks($bookName): array
    {
        return $this->findExistRow("Book", "Title", $bookName, true, true); 
    }

    /**
     * 根據書的ID找出一本書
     *
     * @param [type] $bookNo    書的ID
     * @return array            書的資料向量
     */
    public function findBookById($bookNo): bool
    {
        return $this->findExistRow("Book", "BookNumber", $bookNo, true)[0]; 
    }

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

}
