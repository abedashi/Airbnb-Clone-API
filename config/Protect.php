<?php  
  require_once "{$_SERVER['DOCUMENT_ROOT']}/classroom-api/vendor/autoload.php";
  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;

  function startsWith ($string, $startString) {
      $len = strlen($startString);
      return (substr($string, 0, $len) === $startString);
  }

  try {
    $auth = getallheaders()["Authorization"];

    if (empty($auth) || !startsWith($auth, "Bearer")) {
      throw new Exception("Unauthorized");
    }

    $token = explode(" ", $auth)[1];
    $key = 'ajdZiWodDaAs1123';

    // Validate token
    $decoded = JWT::decode($token, new Key($key, 'HS256'));

    $user_id = $decoded->data->id;
    $role = $decoded->data->role;
    
  }
  catch (Exception $e) {
    throw new Exception("Unauthorized");
  }
?>