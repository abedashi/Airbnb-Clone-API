<?php
class WatchList {
    private $conn;
    private $table = 'watch_list';

    // watchlist props
    public $id;
    public $userId;
    public $appartment_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} (userId, appartment_id) VALUES ( ?, ?)"
        );

        $query->bind_param("ii", $this->userId, $this->appartment_id);

        if ($query->executr()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }

    public function get() {
        $query = $this->conn->prepare(
            "SELECT * FROM {$this->table} 
                 JOIN users ON watch_list.userId = users.id 
                 JOIN appartments_list ON watch_list.appartment_id = appartments_list.id 
                 ORDER BY appartments_list.created_at DESC"
        );
        $query->execute();
        $result = $query->get_result();

        return $result;
    }
    
    public function getSingle() {
        $query = $this->conn->prepare(
            "SELECT * FROM {$this->table} 
                 JOIN users ON watch_list.userId = users.id 
                 JOIN appartments_list ON watch_list.appartment_id = appartments_list.id 
                 WHERE watch_list.appartment_id = ? AND watch_list.userId = ? 
                 ORDER BY appartments_list.created_at DESC"
        );
        $query->bind_param('ii', $this->appartment_id, $this->userID);
        $query->execute();
        $result = $query->get_result();

        return $result;
    }

    public function delete() {
        $query = $this->conn->prepare(
            "DELETE FROM watch_list WHERE id = ?"
        );

        $query->bind_param('i', $this->id);

        $query->execute();
    }
}
