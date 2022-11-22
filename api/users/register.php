<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../vendor/autoload.php';
  use Firebase\JWT\JWT;
  require_once '../../config/Database.php';
  require_once '../../models/User.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $user = new User($db);

  try {
    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    // Validate input fields
    if (empty($data->username) || empty($data->password) 
        || empty($data->passwordConfirm)) {
      throw new Exception("Please enter all fields");
    }

    $user->username = $data->username;
    $user->password = $data->password;
    $user->passwordConfirm = $data->passwordConfirm;

    // Register user
    if ($insertID = $user->register()) {
      // Create token
      $key = 'ajdZiWodDaAs1123';
      $iat = time();
      $payload = [
        'iss' => 'localhost',
        'aud' => 'localhost',
        'iat' => $iat,
        'nbf' => $iat,
        'exp' => $iat + 259200000, // 3 days
        'data' => [
          "id" => $insertID
        ]
      ];
      $token = JWT::encode($payload, $key, 'HS256');

      http_response_code(201);
      echo json_encode(
        array(
          "id" => $insertID,
          "username" => $user->username,
          "token" => $token
        )
      );
    }
  }
  catch (Exception $e) { 
    http_response_code(400);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }

?>