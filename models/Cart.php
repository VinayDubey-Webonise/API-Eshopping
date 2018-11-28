<?php
  class Cart {
    // Database fields
    private $connection;
    private $table = 'carts';

    // Properties
    public $id;
    public $user_id;
    public $product_id;
    public $user_name,
            $product_name,
            $total_price,
            $total_discount,
            $total_after_discount,
            $total_tax,
            $total_after_tax,
            $grand_total;
    
    // Constructor with DB
    public function __construct($database_connection) {
      $this->connection = $database_connection;
    }

    // Get cart username, Products, Total, Total Discount, Total with Discount, Total Tax, Total With Tax, Grand Total
    public function read() {
      // Create query
      $query = "SELECT u.name as user_name, 
              GROUP_CONCAT(p.name) as product_name, 
              SUM(p.price) as total_price,
              SUM((p.price*p.discount)/100) as total_discount,
              SUM(p.price) - SUM((p.price*p.discount)/100) as total_after_discount,
              SUM((p.price*categories.tax)/100) as total_tax,
              SUM(p.price) + SUM((p.price*categories.tax)/100) as total_after_tax,
              SUM(p.price) - SUM((p.price*p.discount)/100) + SUM((p.price*categories.tax)/100) as grand_total
            FROM $this->table c
              INNER JOIN users u ON u.id = c.user_id
              INNER JOIN products p ON p.id = c.product_id
              INNER JOIN categories ON categories.id = p.category_id
              GROUP BY u.id";

      // Prepare statement
      $statement = $this->connection->prepare($query);

      // Execute query
      $statement->execute();

      return $statement;
    }
    
    // Get Single Cart
    public function read_single(){
      // Create query
      $query = "SELECT u.name as user_name, GROUP_CONCAT(p.name) as product_name, SUM(p.price) as total_price,
              SUM((p.price*p.discount)/100) as total_discount,
              SUM(p.price) - SUM((p.price*p.discount)/100) as total_after_discount,
              SUM((p.price*categories.tax)/100) as total_tax,
              SUM(p.price) + SUM((p.price*categories.tax)/100) as total_after_tax,
              SUM(p.price) - SUM((p.price*p.discount)/100) + SUM((p.price*categories.tax)/100) as grand_total
            FROM $this->table c
              INNER JOIN users u ON u.id = c.user_id
              INNER JOIN products p ON p.id = c.product_id
              INNER JOIN categories ON categories.id = p.category_id
            WHERE u.id = ?
              GROUP BY u.id";

        //Prepare statement
        $statement = $this->connection->prepare($query);

        // Bind ID
        $statement->bindParam(1, $this->user_id);

        // Execute query
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->user_name = $row['user_name'];
        $this->product_name = $row['product_name'];
        $this->total_price = $row['total_price'];
        $this->total_discount = $row['total_discount'];
        $this->total_after_discount = $row['total_after_discount'];
        $this->total_tax = $row['total_tax'];
        $this->total_after_tax = $row['total_after_tax'];
        $this->grand_total = $row['grand_total'];
    }
    
    // Insert into database
    public function insert() {
      // Create query
      $query = 'INSERT INTO ' . $this->table . ' SET user_id = :user_id, product_id = :product_id';
      
      // Prepare statement
      $statement = $this->connection->prepare($query);

      // Bind paramaters
      $statement->bindParam(':user_id', $this->user_id);
      $statement->bindParam(':product_id', $this->product_id);

      // Execute query
      if($statement->execute()) {
        return true;
      }

      printf('Error : '.$statement->error);
      return false;
    }

    // Delete data based on user id inside cart
    public function delete() {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE user_id = :user_id';
      
      // Prepare statement
      $statement = $this->connection->prepare($query);

      // Bind parameters
      $statement->bindParam(':user_id', $this->user_id);

      // Execute statement
      if($statement->execute()) {
        return true;
      }

      printf("Error : ".$statement->error);
      return false;
    }

    // Delete data based on user id and product inside cart
    public function delete_single() {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE user_id = :user_id AND product_id = :product_id LIMIT 1';
      
      // Prepare statement
      $statement = $this->connection->prepare($query);

      // Bind parameters
      $statement->bindParam(':user_id', $this->user_id);
      $statement->bindParam(':product_id', $this->product_id);

      // Execute statement
      if($statement->execute()) {
        return true;
      }

      printf("Error : ".$statement->error);
      return false;
    }

    public function cart_total() {
      // Create query
      $query = 'SELECT users.name as user_name,
                 SUM(products.price) - SUM((products.price*products.discount)/100) + SUM((products.price*categories.tax)/100) as grand_total
                FROM users
                  INNER JOIN carts ON users.id = carts.user_id
                  INNER JOIN products ON products.id = carts.product_id
                  INNER JOIN categories ON categories.id = products.category_id
                WHERE users.id = ?
                GROUP BY users.id';
      
        //Prepare statement
        $statement = $this->connection->prepare($query);

        // Bind ID
        $statement->bindParam(1, $this->user_id);

        // Execute query
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->user_name = $row['user_name'];
        $this->grand_total = $row['grand_total'];
    }
    
    public function cart_total_discount() {
      // Create query
      $query = 'SELECT users.name as user_name,
                 SUM((products.price*products.discount)/100) as total_discount
                FROM users
                  INNER JOIN carts ON users.id = carts.user_id
                  INNER JOIN products ON products.id = carts.product_id
                WHERE users.id = ?
                GROUP BY users.id';
      
        //Prepare statement
        $statement = $this->connection->prepare($query);

        // Bind ID
        $statement->bindParam(1, $this->user_id);

        // Execute query
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->user_name = $row['user_name'];
        $this->total_discount = $row['total_discount'];
    }

    public function cart_total_tax() {
      // Create query
      $query = 'SELECT users.name as user_name,
                 SUM((products.price*categories.tax)/100) as total_tax
                FROM users
                  INNER JOIN carts ON users.id = carts.user_id
                  INNER JOIN products ON products.id = carts.product_id
                  INNER JOIN categories ON categories.id = products.category_id
                WHERE users.id = ?
                GROUP BY users.id';
      
        //Prepare statement
        $statement = $this->connection->prepare($query);

        // Bind ID
        $statement->bindParam(1, $this->user_id);

        // Execute query
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->user_name = $row['user_name'];
        $this->total_tax = $row['total_tax'];
    }
  }
