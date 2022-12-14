<?php
class Coordinates {
    private $conn;
    private $table = "coordinates";

    // coordinates Props
    public $id;
    public $appartment_id;
    public $lat;
    public $lng;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Prepare statment
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} (appartment_id, lat, lng) VALUES (?, ?, ?)"
        );

        $query->bind_param("idd", $this->appartment_id, $this->lat, $this->lng);

        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }
}
?>