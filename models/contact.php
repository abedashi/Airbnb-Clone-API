<?php 
class Contact {
    private $conn;
    private $table = 'contact';

    // contact props
    public $id;
    public $userId;
    public $hostId;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} (userId, hostId) VALUES (?, ?)"
        );

        $query->bind_param("ii", $this->userId, $this->hostId);

        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }

    public function getSingle() {
        $query = $this->conn->prepare(
            "SELECT username, image, contact.hostId FROM users
                JOIN contact ON contact.hostId = users.id
             WHERE contact.hostId = ?"
        );
        $query->bind_param('i', $this->hostId);
        $query->execute();
        $result = $query->get_result();

        return $result;
    }
    

}
