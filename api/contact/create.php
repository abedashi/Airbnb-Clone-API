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
  require_once '../../models/contact.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $contact = new Contact($db);

  try {
    // Protect route
    require '../../config/protect.php';

    $contact->hostId = htmlspecialchars($_GET["hostId"]);
    $contact->userId = $userId;
    
    if ($insertId = $contact->create()) {
      $contact->id = $insertId;
      http_response_code(201);
    } else {
      throw new Exception("contact Failed");
    }

  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }
?>