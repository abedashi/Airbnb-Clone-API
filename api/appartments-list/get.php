<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  require_once '../../config/Database.php';
  require_once '../../models/AppartmentsList.php';

    // Connect db
    $database = new Database();
    $db = $database->connect();
  
    $appartment = new AppartmentsList($db);

    try {
      // Protect route
      require '../../config/protect.php';

      $appartment->class_id = htmlspecialchars($_GET["class_id"]);

      if($result = $appartment->get()) {
        $response = [];
        while ($row = $result->fetch_assoc()) {
          $response[] = $row;
        }
        http_response_code(200);
        echo json_encode($response); 
      }

    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(
        array('message' => $e->getMessage())
      );
    }
?>