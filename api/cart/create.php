<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Cart.php';

    
  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate cart object
  $cart = new Cart($database_connection);

  // Get raw data
  $raw_data = json_decode(file_get_contents("php://input"));
  
  // Set value into cart object
  $cart->user_id = $raw_data->user_id;
  $cart->product_id = $raw_data->product_id;

  // Insert into database
  if($cart->insert()) {
    echo json_encode(
      array('Response message' => 'New record created')
    );
  }
  else {
    echo json_encode(
      array('Response message ' => 'Record failed to insert')
    );
  }
  
