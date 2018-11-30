<?php
  class Category {
    // Database fields
    private $connection;
    private $table = 'categories';

    // Properties
    public $id;
    public $name;
    public $description;
    public $tax;

    // Constructor with DB
    public function __construct($database_connection) {
      $this->connection = $database_connection;
    }

    // Get categories
    public function read() {
      // Create query
      $query = 'SELECT
        id,
        name,
        description,
        tax
      FROM
        ' . $this->table;

      // Prepare statement
      $statement = $this->connection->prepare($query);

      // Execute query
      $statement->execute();

      return $statement;
    }
    
    // Get Single Category
    public function read_single(){
      // Create query
      $query = 'SELECT
            id,
            name,
            description,
            tax
          FROM
            ' . $this->table . '
        WHERE id = ?
        LIMIT 0,1';

        //Prepare statement
        $statement = $this->connection->prepare($query);

        // Bind ID
        $statement->bindParam(1, $this->id);

        // Execute query
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->tax = $row['tax'];
    }

    // Insert into database
    public function insert() {
      // Create query
      $query = 'INSERT INTO ' . $this->table . ' SET name = :name, description = :description, tax = :tax';
      
      // Prepare statement
      $statement = $this->connection->prepare($query);

      // Bind paramaters
      $statement->bindParam(':name', $this->name);
      $statement->bindParam(':description', $this->description);
      $statement->bindParam(':tax', $this->tax);

      // Execute query
      if($statement->execute()) {
        return true;
      }

      printf('Error : '.$statement->error);
      return false;
    }

    // Update values in database
    public function update() {
      // Create query
      $query = 'UPDATE ' . $this->table . ' SET name = :name, description = :description, tax = :tax WHERE id = :id';

      // Prepare statement
      $statement = $this->connection->prepare($query);

      // Bind parameters
      $statement->bindParam(':id', $this->id);
      $statement->bindParam(':name', $this->name);
      $statement->bindParam(':description', $this->description);
      $statement->bindParam(':tax', $this->tax);

      // Execute statement
      if($statement->execute()) {
        return true;
      }

      printf('Error : '.$statement->error);
      return false;
    }

    public function delete() {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
      
      // Prepare statement
      $statement = $this->connection->prepare($query);

      // Bind parameters
      $statement->bindParam(':id', $this->id);

      // Execute statement
      if($statement->execute()) {
        return true;
      }

      printf("Error : ".$statement->error);
      return false;
    }

  }
