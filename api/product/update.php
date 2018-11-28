<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Product.php';

  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate product object
  $product = new Product($database_connection);

  // Get raw posted data
  $raw_data = json_decode(file_get_contents("php://input"));

  // Set all the raw data
  $product->id = $raw_data->id;
  $product->name = $raw_data->name;
  $product->description = $raw_data->description;
  $product->price = $raw_data->price;
  $product->discount = $raw_data->discount;
  $product->category_id = $raw_data->category_id;

  // Update data
  if($product->update()) {
      echo json_encode(
          array("Response message" => "Record updated")
      );
  }
  else {
    echo json_encode(
        array("Response message" => "Record failed to update")
    );
  }

