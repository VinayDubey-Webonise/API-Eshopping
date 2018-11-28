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

  // Cart read query
  $result = $cart->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any carts
  if($num > 0) {
        // Cart array
        $cart_arr = array();
        $cart_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $cart_item = array(
            'user_name' => $user_name,
            'product_name' => $product_name,
            'total_price' => $total_price,
            'total_discount' => $total_discount,
            'total_after_discount' => $total_after_discount,
            'total_tax' => $total_tax,
            'total_after_tax' => $total_after_tax,
            'grand_total' => $grand_total
          );

          // Push to "data"
          array_push($cart_arr['data'], $cart_item);
        }

        // Turn to JSON & output
        echo json_encode($cart_arr);

  } else {
        // No Cart
        echo json_encode(
          array('message' => 'No cart Found')
        );
  }
