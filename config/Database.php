<?php
    class Database {
        // DB Params
        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $db = "rsp";
        private $conn;

        //DB Connect
        public function connect() {
            $this->conn = null;

            try {
                $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db);
            } catch(Exception $e) {
                echo 'Conection Error: ' . $e->getMessage();
            }

            return $this->conn;
        }
    }
?>