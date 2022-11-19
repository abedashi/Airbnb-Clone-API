<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  require_once '../../config/Database.php';
  require_once '../../models/Watchlist.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $watchlist = new WatchList($db);

  try {
    // Protect route
    require '../../config/protect.php';

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    $watchlist->userId = $userId;
    $watchlist->appartment_id = $data->appartment_id;


    if ($insertId = $watchlist->create()) {
      $watchlist->id = $insertId;

      $response = $watchlist->getSingle();

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