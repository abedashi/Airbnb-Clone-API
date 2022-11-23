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
            "INSERT INTO {$this->table} (appartment_id, image1, image2, image3, image4, image5) VALUES (?, ?, ?, ?, ?, ?)"
        );

        $query->bind_param("isssss",
            $this->appartment_id, $this->image1, $this->image2,
            $this->image3, $this->image4, $this->image5
        );
        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }
}
?>