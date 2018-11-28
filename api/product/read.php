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

  // Product read query
  $result = $product->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any products
  if($num > 0) {
        // Products array
        $product_arr = array();
        $product_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $product_item = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'discount' => $discount,
            'category_id' => $category_id
          );

          // Push to "data"
          array_push($product_arr['data'], $product_item);
        }

        // Turn to JSON & output
        echo json_encode($product_arr);

  } else {
        // No Products
        echo json_encode(
          array('message' => 'No Products Found')
        );
  }
