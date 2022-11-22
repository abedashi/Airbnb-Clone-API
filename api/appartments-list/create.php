<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
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

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    $appartment->userId = $userId;
    $appartment->appName = $data->appName;
    $appartment->address = $data->address;
    $appartment->guests = $data->guests;
    $appartment->price = $data->price;
    $appartment->bedroom = $data->bedroom;
    $appartment->bed = $data->bed;
    $appartment->bath = $data->bath;
    $appartment->created_at = $data->created_at;

    if ($insertId = $appartment->create()) {
      $appartment->id = $insertId;

      $response = $appartment->get();

      http_response_code(201);
      echo json_encode($response->fetch_assoc());
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