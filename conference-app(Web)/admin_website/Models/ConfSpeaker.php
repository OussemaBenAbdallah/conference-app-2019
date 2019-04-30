<?php
/**
 * Created by PhpStorm.
 * User: oussema
 * Date: 3/23/2019
 * Time: 8:15 PM
 */

include_once 'Model.php';
class ConfSpeaker extends Model
{
    public function __construct($db)
    {
        parent::__construct($db);
    }
    public function addConfSpeaker($id_conf,$id_speaker) {

        $query = 'INSERT INTO conf_speaker SET ID_CONF='.$id_conf.', ID_SPEAKER='.$id_speaker;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

    }

    public function deleteConfSpeaker($id_conf) {

        $query = 'DELETE FROM conf_speaker WHERE ID_CONF='.$id_conf;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

    }

}