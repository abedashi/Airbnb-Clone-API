<?php 
class User {
  private $conn;
  private $table = 'users';

  // User props
  public $id;
  public $role;
  public $fname;
  public $lname;
  public $email;
  public $password;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function register() {

    // Check if email is taken
    $query = $this->conn->prepare(
      "SELECT email FROM {$this->table} WHERE email = ?"
    );

    // Bind email and execute
    $query->bind_param("s", $this->email);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
      throw new Exception("Email already exists");
    }

    // Create query and prepare to register user
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} VALUES(NULL,?, ?, ?, ?, ?)"
    );

    // Hash password
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);

    // Bind data
    $query->bind_param("sssss", $this->role ,$this->fname, $this->lname, $this->email, $this->password);

    // Execute Query
    if ($query->execute()) {
      return $query->insert_id;
    }

    throw new Exception('Error: %s\n',$this->conn->error);
  }

  public function login() {
    $query = $this->conn->prepare(
      "SELECT id,role, password, fname, lname FROM {$this->table} WHERE email = ?"
    );

    $query->bind_param("s", $this->email);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows === 0) {
      throw new Exception("Invalid Credentials");
    }
    
    $result = $result->fetch_assoc();

    if (password_verify($this->password, $result["password"])) {
      $this->id = $result["id"];
      $this->role = $result["role"];
      $this->fname = $result["fname"];
      $this->lname = $result["lname"];
      return true;
    }

    return false;
  }

  public function updateRole() {
    $query = $this->conn->prepare(
      "UPDATE users SET role = ? WHERE id = ?"
    );

    $query->bind_param('si', $this->role, $this->id);
    if ($query->execute()) {
      return true;
    }
    return false;
  }
}
?>