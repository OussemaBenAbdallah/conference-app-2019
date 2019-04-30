<?php

include_once 'Model.php';
class Activity extends Model
{
    public $id_activity = null;
    public $activity_name;


    public function __construct($db)
    {
        parent::__construct($db);
        $this->table_name = "activities";
    }

    function addActivity()
    {
        if ($this->exist()) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " SET ACTIVITY_NAME=:activity_name";
        $stmt = $this->conn->prepare($query);

        $this->activity_name = htmlspecialchars(strip_tags($this->activity_name));

        $stmt->bindParam(":activity_name", $this->activity_name);

        if ($stmt->execute()) {
            $this->id_activity = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    private function exist()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE activity_name = '" . $this->activity_name . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }


    function getActivity()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE activity_name='" . $this->activity_name . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = PDO::FETCH_ASSOC();

        $activity = [
            'id_activity' => $row['id_activity'],
            'activity_name' => $row['activity_name']
        ];
        return json_encode($activity);
    }

    function getAllActivities()
    {
        $activities = array();
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($activities, [
                'id_activity' => $row['id_activity'],
                'activity_name' => $row['activity_name']
            ]);
        }
        $activitiesJSON = json_encode($activities);
        return $activitiesJSON;
    }

    public function getIdByName()
    {
        $query = "SELECT id_activity FROM " . $this->table_name . " WHERE activity_name = '" . $this->activity_name."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_activity'];
    }

}