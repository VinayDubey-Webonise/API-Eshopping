<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Product.php';

  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate product object
  $product = new Product($database_connection);

  // Get raw data
  $raw_data = json_decode(file_get_contents("php://input"));
  
  // Set value into product object
  $product->name = $raw_data->name;
  $product->description = $raw_data->description;
  $product->price = $raw_data->price;
  $product->discount = $raw_data->discount;
  $product->category_id = $raw_data->category_id;

  // Insert into database
  if($product->insert()) {
    echo json_encode(
      array('Response message' => 'New record created')
    );
  }
  else {
    echo json_encode(
      array('Response message ' => 'Record failed to insert')
    );
  }
  
