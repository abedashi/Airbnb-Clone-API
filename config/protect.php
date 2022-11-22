<?php  
  require_once "{$_SERVER['DOCUMENT_ROOT']}/Airbnb-Clone-API/vendor/autoload.php";
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

    $userId = $decoded->data->id;
    
  }
  catch (Exception $e) {
    throw new Exception("Unauthorized");
  }
?>