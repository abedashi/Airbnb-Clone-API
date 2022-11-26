<?php
class WatchList
{
    private $conn;
    private $table = 'watch_list';

    // watchlist props
    public $id;
    public $userId;
    public $appartment_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = $this->conn->prepare(
            "INSERT INTO {$this->table} (userId, appartment_id) VALUES (?, ?)"
        );

        $query->bind_param("ii", $this->userId, $this->appartment_id);

        if ($query->execute()) {
            return $query->insert_id;
        } else {
            return false;
        }
    }
    public function get()
    {
        $query = $this->conn->prepare(
            "SELECT watch_list.appartment_id, watch_list.userId, appName, address, guests, price, bedroom, bed, bath, lat, lng,
              image1, image2, image3, image4, image5, wifi, parking, tv, ac, smoke, electricity, created_at,
              image, username FROM appartments_list
                 JOIN users ON appartments_list.userId = users.id 
                 JOIN watch_list ON watch_list.appartment_id = appartments_list.id
                 JOIN coordinates ON appartments_list.id = coordinates.appartment_id
                 JOIN offers ON appartments_list.id = offers.appartment_id
                 JOIN images ON appartments_list.id = images.appartment_id
                 WHERE watch_list.userId = ?"
        );
        $query->bind_param('i', $this->userId);
        $query->execute();
        $result = $query->get_result();

        return $result;
    }

    public function getSingle()
    {
        $query = $this->conn->prepare(
            "SELECT watch_list.appartment_id, watch_list.userId, appName, address, guests, price, bedroom, bed, bath, lat, lng,
              image1, image2, image3, image4, image5, wifi, parking, tv, ac, smoke, electricity, created_at,
              image, username FROM appartments_list
                 JOIN users ON appartments_list.userId = users.id 
                 JOIN watch_list ON watch_list.appartment_id = appartments_list.id
                 JOIN coordinates ON appartments_list.id = coordinates.appartment_id
                 JOIN offers ON appartments_list.id = offers.appartment_id
                 JOIN images ON appartments_list.id = images.appartment_id
                 WHERE watch_list.userId = ? AND watch_list.appartment_id = ?"
        );
        $query->bind_param('ii', $this->userId, $this->appartment_id);
        $query->execute();
        $result = $query->get_result();

        return $result;
    }

    public function delete()
    {
        $query = $this->conn->prepare(
            "DELETE FROM watch_list WHERE id = ?"
        );

        $query->bind_param('i', $this->id);

        $query->execute();
    }
}
