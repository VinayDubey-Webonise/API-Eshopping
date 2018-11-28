<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate category object
  $category = new Category($database_connection);

  // Get raw posted data
  $raw_data = json_decode(file_get_contents("php://input"));

  // Set poseted data
  $category->id = $raw_data->id;

  // Delete record call
  if($category->delete()) {
      echo json_encode(
          array("Response message " => "Record deleted")
      );
  }
  else {
    echo json_encode(
        array("Response message " => "Record failed to delete")
    );
  }

