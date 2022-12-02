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
  require_once '../../models/chat.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $chat = new Chat($db);

  try {
    // Protect route
    require '../../config/protect.php';

    $data = json_decode(file_get_contents("php://input"));
    $chat->contactId = htmlspecialchars($_GET["contactId"]);
    $chat->userId = $userId;

    $chat->message = $data->message;

    if ($insertId = $chat->createChat()) {
      $chat->id = $insertId;
      http_response_code(201);
    } else {
      throw new Exception("chat Failed");
    }

  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }
?>