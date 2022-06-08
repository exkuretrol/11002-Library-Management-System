<?php
declare (strict_types = 1);
namespace Classes;

class Database
{
    private $conn;

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

    public function execute($sql, $simple = false)
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

    public function findExistRow($table, $attr, $target, $returnResult = false)
    {
        if (!$this->conn) {
            $this->getConnection();
        }
        try {
            $sql = "select * from `$table` where `$attr` = ?;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array($target));
            if ($returnResult) $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (!$returnResult) $result = $stmt->rowCount() !==0 ? true : false;
            return $result;
        } catch (PDOException $e) {
            echo "<p>" . $exception->getMessage() . "</p>";
            exit;
        }
    }

    public function auth($userEmail, $userPass) {
        $row = $this->findExistRow("Reader", "Email", $userEmail, true);

        if (count($row) == 0) {
            $auth = false;
            return;
        }

        $row = $row[0];

        $auth = ($userEmail == $row["Email"] & $userPass == $row["Password"]) ? true : false;
        return $auth;
    }

}
