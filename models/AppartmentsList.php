<?php
class AppartmentsList {
    private $conn;
    private $table = "appartmentslist";

    // Appartment-list props
    public $id;
    public $userId;
    public $appName;
    public $address;
    public $guests;
    public $price;
    public $bedroom;
    public $bed;
    public $bath;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        //Prepare statment
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $query->bind_param("issiiiiis",
            $this->userId, $this->appName, $this->address, $this->guests,
            $this->price, $this->bedroom, $this->bed, $this->bath, $this->created_at
        );

        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }

    public function get() {
        // Preapare statment
        $query = $this->conn-prepare(
            "SELECT * FROM {$this->table}
                JOIN users ON appartments_list.userId = users.id
                JOIN coordinates ON appartments_list.id = coordinates.appartment_id
                JOIN offers ON appartments_list.id = offers.appartment_id
                JOIN images ON appartments_list.id = images.appartment_id
                ORDER BY created_at DESC"
        );
        // Execution 
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function get_single() {
        // Preapare statment
        $query = $this->conn->prepare(
            "SELECT * FROM {$this->table}
                JOIN users ON appartments_list.userId = users.id
                JOIN coordinates ON appartments_list.id = coordinates.appartment_id
                JOIN offers ON appartments_list.id = offers.appartment_id
                JOIN images ON appartments_list.id = images.appartment_id
                WHERE appartments_list.id = ?"
        );
        //Binding
        $query->bind_param("i", $this->id);
        // Execution
        $query->execute();
        $response = $query->get_result();

        return $response;
    }

    public function delete() {
        $query = $this->conn->prepare(
            "DELETE FROM {$this->table} WHERE id = ?"
        );
        $query->bind_param("i", $this->id);
        $query->execute();
    }
}
?>