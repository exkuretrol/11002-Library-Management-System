<?php
declare(strict_types=1);
namespace Library;

class Database
{
  private $conn;

  public function getConnection() : \PDO
  {
    // 如果連線不存在
    if (!$this->conn)
    {
      try {
        $this->conn = new \PDO(
          "mysql:host={$_ENV['host']}:{$_ENV['port']};dbname={$_ENV['name']}",
          $_ENV['user'],
          $_ENV['pass']
        );
        $this->conn->exec("set names utf8");
      } catch(PDOException $exception) {
        echo "Database error";
      }
    }
    return $this->conn;
  }
}