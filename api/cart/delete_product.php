<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Cart.php';

    
  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate cart object
  $cart = new Cart($database_connection);

  // Get raw posted data
  $raw_data = json_decode(file_get_contents("php://input"));

  // Set posted data
  $cart->user_id = $raw_data->user_id;
  $cart->product_id = $raw_data->product_id;

  // Delete record call
  if($cart->delete_single()) {
      echo json_encode(
          array("Response message " => "Cart item deleted")
      );
  }
  else {
    echo json_encode(
        array("Response message " => "Cart failed to delete item")
    );
  }

