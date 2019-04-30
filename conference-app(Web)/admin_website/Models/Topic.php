<?php
include_once 'Model.php';

class Topic extends Model
{
    public $id_topic = null;
    public $topic_name;

    public function __construct($db)
    {
        parent::__construct($db);
        $this->table_name = "topics";
    }

    function addTopic()
    {
        if ($this->exist()) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " SET topic_name=:topic_name ";

        $stmt = $this->conn->prepare($query);

        $this->topic_name = htmlspecialchars(strip_tags($this->topic_name));
        $stmt->bindParam(":topic_name", $this->topic_name);
        if ($stmt->execute()) {
            $this->id_topic = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    private function exist()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE topic_name='" . $this->topic_name . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getTopic()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE topic_name='" . $this->topic_name . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = PDO::FETCH_ASSOC();

        $topic = [
            'id_topic' => $row['id_topic'],
            'topic_name' => $row['topic_name']
        ];
        return json_encode($topic);
    }


    function getAllTopics()
    {
        $topics = array();
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($topics, [
                'id_topic' => $row['id_topic'],
                'topic_name' => $row['topic_name']
            ]);
        }
        $topicsJSON = json_encode($topics);
        return $topicsJSON;
    }

    function getIdByName()
    {
        $query = "SELECT id_topic FROM " . $this->table_name . " WHERE topic_name = '" . $this->topic_name."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_topic'];
    }


}