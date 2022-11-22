<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE'); //DELETE, POST, GET, PUT
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  require_once '../../config/Database.php';
  require_once '../../models/AppartmentsList.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $appartment = new AppartmentsList($db);

  try {
    require '../../config/protect.php';
    $data = json_decode(file_get_contents("php://input"));
    $appartment->id = $data->id;
    $appartment->delete();
  } catch (Exception $e) {

  }
?>