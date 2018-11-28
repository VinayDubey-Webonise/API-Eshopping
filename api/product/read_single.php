<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Product.php';

  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate product object
  $product = new Product($database_connection);

  // Get ID
  $product->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get post
  $product->read_single();

  // Create array
  $product_arr = array(
    'id' => $product->id,
    'name' => $product->name,
    'description' => $product->description,
    'price' => $product->price,
    'discount' => $product->discount,
    'category_id' => $product->category_id
  );

  // Make JSON

  // Make JSON
  if($product->name == null) {
    print_r(json_encode(array('Response' => 'No record found')));  
  }
  else {
    print_r(json_encode($product_arr));
  }
