<?php
/**
 * Created by PhpStorm.
 * User: oussema
 * Date: 3/24/2019
 * Time: 1:19 PM
 */

include_once 'Model.php';

class ConfDateLoc extends Model
{
    public $table_name = "conf_date_loc";
    public $id_conf;
    public $id_activity;
    public $id_location;
    public $id_edition;
    public $key_date;
    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function addConfDateLoc()
    {
        $query = "INSERT INTO " . $this->table_name
            . " SET id_conf=:id_conf, id_activity=:id_activity, id_location=:id_location,
                    id_edition=:id_edition,key_date=:key_date";
        $stmt = $this->conn->prepare($query);

        $this->id_conf = htmlspecialchars(strip_tags($this->id_conf));
        $this->id_activity = htmlspecialchars(strip_tags($this->id_activity));
        $this->id_location = htmlspecialchars(strip_tags($this->id_location));
        $this->id_edition = htmlspecialchars(strip_tags($this->id_edition));
        $this->key_date = htmlspecialchars(strip_tags($this->key_date));

        $stmt->bindParam(':id_conf',$this->id_conf);
        $stmt->bindParam(':id_activity',$this->id_activity);
        $stmt->bindParam(':id_location',$this->id_location);
        $stmt->bindParam(':id_edition',$this->id_edition);
        $stmt->bindParam(':key_date',$this->key_date);

        return $stmt->execute();

    }

    public function deleteConfDateLoc($id_conf){
        $query = "DELETE FROM " . $this->table_name
            . " WHERE id_conf = ".$id_conf;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

    }
}