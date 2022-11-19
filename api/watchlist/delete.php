<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE'); //DELETE, POST, GET, PUT
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  require_once '../../config/Database.php';
  require_once '../../models/Watchlist.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $watchlist = new WatchList($db);

  try {
    require '../../config/protect.php';
    $data = json_decode(file_get_contents("php://input"));
    $watchlist->id = $data->id;
    $watchlist->delete();
  } catch (Exception $e) {

  }


?>