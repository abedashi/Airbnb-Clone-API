<?php 
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
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
    // Protect route
    require '../../config/protect.php';

    $data = json_decode(file_get_contents("php://input"));

    $user->id = $userId;
    $user->job = $data->job;
    $user->about = $data->about;
    $user->location = $data->location;
    $user->language = $data->language;

    if ($user->updateProfile()) {
      http_response_code(204);
    }
    else {
      throw new Exception("Update Failed");
    }

  } catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
      "message" => $e->getMessage()
    ]);
  }
?>