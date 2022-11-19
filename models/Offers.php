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
            "INSERT INTO {$this->table} VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)"
        );

        $query->bind_param("ibbbbbb",
            $this->appartment_id, $this->wifi, $this->parking,
            $this->tv, $this->ac, $this->smoke, $this->electricity
        );

        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }
}
?>