<?php 
  class Database {
    // DB Params
    private $host = 'localhost';
    private $database_name = 'eshopping';
    private $username = 'root';
    private $password = '';
    private $connection;
    private static $conn;

    // DB Connect
    public function connect() {
      try { 
        $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database_name, $this->username, $this->password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $pdoException) {
        echo 'Connection Error: ' . $pdoException->getMessage();
      }

      return $this->connection;
    }

    public static function get() {
      if (null === static::$conn) {
        static::$conn = new static();
      }

      return static::$conn;
    }

    private function __clone() {
      
    }
  }