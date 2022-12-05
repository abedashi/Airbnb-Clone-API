<?php 
class Contact {
    private $conn;
    private $table = 'contact';

    // contact props
    public $id;
    public $userId;
    public $hostId;
    public $message;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} (userId, hostId, message) VALUES (?, ?, ?)"
        );

        $query->bind_param("iis", $this->userId, $this->hostId, $this->message);

        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }

    public function getSingle() {
        $query = $this->conn->prepare(
            "SELECT username, image, contact.userId ,contact.hostId, contact.message, contact.created_at FROM users
                JOIN contact ON contact.hostId = users.id
             WHERE contact.userId = ? ORDER BY created_at DESC"
        );
        $query->bind_param('i', $this->hostId);
        $query->execute();
        $result = $query->get_result();

        return $result;
    }
    

}
