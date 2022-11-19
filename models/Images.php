<?php
class Images {
    private $conn;
    private $table = "images";

    public $id;
    public $appartment_id;
    public $image1;
    public $image2;
    public $image3;
    public $image4;
    public $image5;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Prepare statment
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} VALUES (NULL, ?, ?, ?, ?, ?, ?)"
        );

        $query->bind_param("isssss");
        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }
}
?>