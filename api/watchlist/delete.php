<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE'); //DELETE, POST, GET, PUT
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/Watchlist.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $watchlist = new WatchList($db);

  try {
    require '../../config/protect.php';
    $watchlist->appartment_id = htmlspecialchars($_GET["appartmentId"]);
    $watchlist->userId = $userId;
    $watchlist->delete();
  } catch (Exception $e) {
    
  }
?>