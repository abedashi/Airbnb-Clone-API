<?php
class Chat {
    private $conn;
    private $table = 'chat';

    // contact props
    public $id;
    public $userId;
    public $message;
    public $contactId;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createChat() {
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} (message, userId, contactId) VALUES (?, ?, ?)"
        );

        $query->bind_param("sii", $this->message, $this->userId, $this->contactId);

        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }

    public function getChat() {
        $query = $this->conn->prepare(
            "SELECT message, chat.userId, created_at, contactId, username FROM {$this->table}
                JOIN users ON users.id = chat.userId
                JOIN contact ON contact.id = chat.contactId
                WHERE chat.contactId = ?"
        );
        $query->bind_param('i', $this->contactId);
        $query->execute();
        $result = $query->get_result();

        return $result;
    }
}
