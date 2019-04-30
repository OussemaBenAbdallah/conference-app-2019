<?php
/**
 * Created by PhpStorm.
 * User: oussema
 * Date: 3/23/2019
 * Time: 6:30 PM
 */

include_once 'Model.php';
class ConfTopic extends Model
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function addConfTopic($id_conf,$id_topic) {

            $query = 'INSERT INTO conf_topic SET ID_CONF='.$id_conf.', ID_TOPIC='.$id_topic;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

    }

    public function deleteConfTopic($id_conf) {

        $query = 'DELETE FROM conf_topic WHERE ID_CONF='.$id_conf;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

    }



}