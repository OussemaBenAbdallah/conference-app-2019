<?php

include_once 'Model.php';
class Location extends Model
{

    public $id_location = null;
    public $name_location;
    public $url_location;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->table_name = "locations";
    }

    function addLocation()
    {
        if ($this->exist()) {
            return true;
        }
        $query = "INSERT INTO " . $this->table_name . " SET name_location=:name, url_location=:url";

        $stmt = $this->conn->prepare($query);

        $this->name_location = htmlspecialchars(strip_tags($this->name_location));
        $this->url_location = htmlspecialchars(strip_tags($this->url_location));

        $stmt->bindParam(':name', $this->name_location);
        $stmt->bindParam(':url', $this->url_location);

        if ($stmt->execute()) {
            $this->id_location = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    private function exist()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name_location ='" . $this->name_location . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }


    function getLocation()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name_location='" . $this->name_location . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = PDO::FETCH_ASSOC();

        $location = [
            'id_location' => $row['id_location'],
            'name_location' => $row['name_location'],
            'url_location' => $row['url_location']
        ];
        return json_encode($location);
    }


    function getAllLocations()
    {
        $locations = array();
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($locations, [
                'id_location' => $row['id_location'],
                'name_location' => $row['name_location'],
                'url_location' => $row['url_location']
            ]);
        }
        $locationsJSON = json_encode($locations);
        return $locationsJSON;
    }

    public function getIdByName()
    {
        $query = "SELECT id_location FROM " . $this->table_name . " WHERE name_location = '" . $this->name_location."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_location'];
    }


}