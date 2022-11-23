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
  require '../../models/AppartmentsList.php';
  require '../../models/Coordinates.php';
  require '../../models/Offers.php';
  require '../../models/Images.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $offers = new Offers($db);
  $coordinates = new Coordinates($db);
  $appartment = new AppartmentsList($db);
  $images = new Images($db);

  try {
    // Protect route
    require '../../config/protect.php';

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));
    // var_dump($data);
    $appartment->userId = $userId;
    $appartment->appName = $data->appName;
    $appartment->address = $data->address;
    $appartment->guests = $data->guests;
    $appartment->price = $data->price;
    $appartment->bedroom = $data->bedroom;
    $appartment->bed = $data->bed;
    $appartment->bath = $data->bath;

    $coordinates->lat = $data->coordinates->lat;
    $coordinates->lng = $data->coordinates->lng;

    $offers->wifi = $data->offers->wifi;
    $offers->parking = $data->offers->parking;
    $offers->tv = $data->offers->tv;
    $offers->ac = $data->offers->ac;
    $offers->smoke = $data->offers->smoke;
    $offers->electricity = $data->offers->electricity;

    $images->image1 = $data->images[0];
    $images->image2 = $data->images[1];
    $images->image3 = $data->images[2];
    $images->image4 = $data->images[3];
    $images->image5 = $data->images[4];

    // var_dump($data);
    if ($insertId = $appartment->create()) {
      // appartment table
      $appartment->id = $insertId;
      
      // coordinates table
      $coordinates->appartment_id = $insertId;
      $coordinates->id = $coordinates->create();

      // offers table
      $offers->appartment_id = $insertId;
      $offers->id = $offers->create();

      // image table
      $images->appartment_id = $insertId;
      $images->id = $images->create();

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