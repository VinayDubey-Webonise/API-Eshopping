<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Cart.php';
  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate cart object
  $cart = new Cart($database_connection);

  // Get ID
  $cart->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();

  // Get post
  $cart->cart_total_discount();

  // Create array
  $cart_arr = array(
    'user_id' => $cart->user_id,
    'user_name' => $cart->user_name,
    'total_discount' => $cart->total_discount
  );

  // Make JSON
  if($cart->user_name == null) {
    print_r(json_encode(array('Response' => 'No record found')));  
  }
  else {
    print_r(json_encode($cart_arr));
  }
