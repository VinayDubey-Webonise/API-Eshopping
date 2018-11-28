<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate category object
  $category = new Category($database_connection);

  // Get raw data
  $raw_data = json_decode(file_get_contents("php://input"));
  
  // Set value into category object
  $category->name = $raw_data->name;
  $category->description = $raw_data->description;
  $category->tax = $raw_data->tax;

  // Insert into database
  if($category->insert()) {
    echo json_encode(
      array('Response message' => 'New record created')
    );
  }
  else {
    echo json_encode(
      array('Response message ' => 'Record failed to insert')
    );
  }
  
