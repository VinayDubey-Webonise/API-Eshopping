<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database_connection = Database::get()->connect();

  // Instantiate category object
  $category = new Category($database_connection);

  // Get ID
  $category->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get post
  $category->read_single();

  // Create array
  $category_arr = array(
    'id' => $category->id,
    'name' => $category->name,
    'description' => $category->description,
    'tax' => $category->tax
  );

  // Make JSON
  if($category->name == null) {
    print_r(json_encode(array('Response' => 'No record found')));  
  }
  else {
    print_r(json_encode($category_arr));
  }
