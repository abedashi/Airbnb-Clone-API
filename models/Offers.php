<?php
class Offers {
    private $conn;
    private $table = "offers";

    public $id;
    public $appartment_id;
    public $wifi;
    public $parking;
    public $tv;
    public $ac;
    public $smoke;
    public $electricity;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Prepare statment
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table}
                (appartment_id, wifi, parking, tv, ac, smoke, electricity)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $query->bind_param("issssss",
            $this->appartment_id, $this->wifi, $this->parking,
            $this->tv, $this->ac, $this->smoke, $this->electricity
        );

        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }
}
?>