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
  require_once '../../models/Reservations.php';

    // Connect db
    $database = new Database();
    $db = $database->connect();
  
    $reservation = new Reservation($db);

    try {
      // Protect route
      require '../../config/protect.php';
      $reservation->appartment_id = htmlspecialchars($_GET["appartment_id"]);
      // $reservation->userId = $userId;

      if($result = $reservation->get()) {
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