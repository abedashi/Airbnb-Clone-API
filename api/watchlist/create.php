<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
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
    // Protect route
    require '../../config/protect.php';

    // Get posted data?appartment_id=2
    $watchlist->appartment_id = htmlspecialchars($_GET["appartment_id"]);
    $watchlist->userId = $userId;
    
    if ($insertId = $watchlist->create()) {
      $watchlist->id = $insertId;
      $response = $watchlist->get(); 
      http_response_code(201);
      echo json_encode($response->fetch_assoc());
    } else {
      throw new Exception("watchlist Failed");
    }

  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }
?>