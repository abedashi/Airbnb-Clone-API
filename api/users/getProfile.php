<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/User.php';

    // Connect db
    $database = new Database();
    $db = $database->connect();
  
    $user = new User($db);

    try {
      require '../../config/protect.php';
      
      // $user->id = htmlspecialchars($_GET["id"]);
      $user->id = $userId;
      
      if($result = $user->getProfile()) {
        http_response_code(200);
        echo json_encode($result->fetch_assoc()); 
      }

    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(
        array('message' => $e->getMessage())
      );
    }
