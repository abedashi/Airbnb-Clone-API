<?php 
class User {
  private $conn;
  private $table = 'users';

  // User props
  public $id;
  public $username;
  public $password;
  public $passwordConfirm;
  public $image;
  public $job;
  public $about;
  public $location;
  public $language;
  public $joined_in;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function register() {
    // Check if email is taken
    $query = $this->conn->prepare(
      "SELECT username FROM {$this->table} WHERE username = ?"
    );

    // Bind email and execute
    $query->bind_param("s", $this->username);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
      throw new Exception("Email already exists");
    }
    if ($this->password !== $this->passwordConfirm) {
      throw new Exception("Password and Password Confirmation didn't matched");
    }
    // Create query and prepare to register user
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (username, password) VALUES (?, ?)"
    );

    // Hash password
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);

    // Bind data
    $query->bind_param("ss", $this->username ,$this->password);

    // Execute Query
    if ($query->execute()) {
      return $query->insert_id;
    }

    throw new Exception('Error: %s\n',$this->conn->error);
  }

  public function login() {
    // Prepare statment
    $query = $this->conn->prepare(
      "SELECT id, password, image, job, about, location, language, joined_in FROM {$this->table} WHERE username = ?"
    );

    // Bind username value to query
    $query->bind_param("s", $this->username);

    // Execute Query 
    $query->execute();

    // Get store the result 
    $result = $query->get_result();
    
    // Check if this username exist in users table
    if ($result->num_rows === 0) {
      throw new Exception("Invalid Credentials");
    }
    
    $result = $result->fetch_assoc();

    // Check if password and username matched
    if (password_verify($this->password, $result["password"])) {
      $this->id = $result["id"];
      $this->job = $result["job"];
      $this->image = $result["image"];
      $this->about = $result["about"];
      $this->location = $result["location"];
      $this->language = $result["language"];
      $this->joined_in = $result["joined_in"];
      return true;
    }

    return false;
  }
}
?>