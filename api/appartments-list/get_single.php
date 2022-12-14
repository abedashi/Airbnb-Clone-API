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
  require_once '../../models/AppartmentsList.php';

    // Connect db
    $database = new Database();
    $db = $database->connect();
  
    $appartment = new AppartmentsList($db);

    try {
      // Protect route
      // require '../../config/protect.php';

      $appartment->id = htmlspecialchars($_GET["id"]);

      if($result = $appartment->get_single()) {
        http_response_code(200);
        echo json_encode($result->fetch_assoc());
      }

    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(
        array('message' => $e->getMessage())
      );
    }
?>