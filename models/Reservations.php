<?php
class Reservation {
    private $conn;
    private $table = 'reservations';

    public $id;
    public $userId;
    public $appatrment_id;
    public $check_in;
    public $check_out;
    public $totalPrice;
    public $nbDays;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} 
                (userId, appartment_id, check_in, check_out, totalPrice, nbDays)
                VALUES (?, ?, ?, ?, ?, ?)"
        );

        $query->bind_param("iissii", 
            $this->userId, $this->appartment_id,
            $this->check_in, $this->check_out,
            $this->totalPrice, $this->nbDays
        );

        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }

    public function get() {
        $query = $this->conn->prepare(
            "SELECT check_in, check_out FROM {$this->table}
                JOIN appartments_list ON reservations.appartment_id = appartments_list.id
                WHERE reservations.appartment_id = ?"
        );

        $query->bind_param("i", $this->appartment_id);
        
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }
}
?>