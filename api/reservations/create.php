<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require '../../config/Database.php';
  require '../../models/Reservations.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $reservation = new Reservation($db);

  try {
    // Protect route
    require '../../config/protect.php';

    $data = json_decode(file_get_contents("php://input"));
    $reservation->check_in = $data->check_in;
    $reservation->check_out = $data->check_out;
    $reservation->totalPrice = $data->totalPrice;
    $reservation->nbDays = $data->nbDays;
    $reservation->appartment_id = htmlspecialchars($_GET["appartment_id"]);
    $reservation->userId = $userId;

    if ($insertId = $reservation->create()) {
      $reservation->id = $insertId;

      // $response = $appartment->get();
      
      http_response_code(201);
      // echo json_encode($response->fetch_assoc());
    } else {
      throw new Exception("Adding New Appartment Failed");
    }

  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }
?>