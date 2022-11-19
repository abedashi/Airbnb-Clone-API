<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Accept, Origin, Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  header('Access-Control-Allow-Headers: ');

  require '../../vendor/autoload.php';
  use Firebase\JWT\JWT;
  require '../../config/Database.php';
  require '../../models/User.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $user = new User($db);

  try {
    // this.URL = "http://localhost/angular/WEBSERVICE_PHP/test.php";

    // const headers = new Headers();
    // headers.append('Content-Type', 'application/json');
    // let data = 'visitor_id=55';

    // return this.http
    // .post(this.URL,JSON.stringify(data),{
    //     headers: headers
    // })
    // .map( Response => console.log(Response) );
    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    if(empty($data->email) || empty($data->password)) {
      throw new Exception("Please enter all fields");
    }

    $user->email = $data->email;
    $user->password = $data->password;
    
    if ($user->login()) {
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
          "id" => $user->id,
        ]
      ];
      $token = JWT::encode($payload, $key, 'HS256');

      http_response_code(200);
      echo json_encode(
        array(
          "id" => $user->id,
          "username" => $user->username,
          "token" => $token
        )
      );
    } else {
      throw new Exception("Invalid credentials");
    }
    
  } catch (Exception $e) {
    http_response_code(400);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }
?>