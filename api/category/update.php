<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate category object
  $category = new Category($database_connection);

  // Get raw posted data
  $raw_data = json_decode(file_get_contents("php://input"));

  // Set all the raw data
  $category->id = $raw_data->id;
  $category->name = $raw_data->name;
  $category->description = $raw_data->description;
  $category->tax = $raw_data->tax;

  // Update data
  if($category->update()) {
      echo json_encode(
          array("Response message" => "Record updated")
      );
  }
  else {
    echo json_encode(
        array("Response message" => "Record failed to update")
    );
  }

