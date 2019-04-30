<?php
class Speaker{

    // database connection and table name
    private $conn;
    private $table_name = "speakers";

    // object properties

    public $id_speaker;
    public $full_name_speaker;

 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // signup user
    function addSpeaker(){
    
        if($this->exist()){
            return false;
        }
        // query to insert record
        $query = "INSERT INTO ".$this->table_name." SET FULL_NAME_SPEAKER=:full_name_speaker";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
		$this->full_name_speaker=htmlspecialchars(strip_tags($this->full_name_speaker));
        // bind values
        $stmt->bindParam(":full_name_speaker", $this->full_name_speaker);
        // execute query
        if($stmt->execute()){
            $this->id_speaker = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    // login user
    /*function login(){
        // select all query
        $query = "SELECT
                     `firstname`, `password`, `created`, `lastname`, `speaker_full_name`, `gsm`
                FROM
                    " . $this->table_name . " 
                WHERE
                    speaker_full_name='".$this->speaker_full_name."' AND password='".$this->password."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }*/

    function exist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                full_name_speaker='".$this->full_name_speaker."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /*public $id_speaker;
    public $full_name_speaker; */

    function getSpeaker(){
		$conferencesArray = [];
		$query = "SELECT
                     `id_speaker`
                FROM
                    " . $this->table_name . " 
                WHERE
                full_name_speaker='".$this->full_name_speaker."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			array_push($conferencesArray, [
			'id_speaker'   => $row['id_speaker']
			]);
		}
		// Convert Array to JSON String
		$conferancesJSON = json_encode($conferencesArray);
		return($conferancesJSON);
		//return($conferencesArray);
	}
	function getAllSpeakers(){
		$conferencesArray = [];
		$query = "SELECT
                     *
                FROM
                    " . $this->table_name;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			array_push($conferencesArray, [
                'id_speaker'   => $row['id_speaker'],
			    'full_name_speaker' => $row['full_name_speaker']
			]);
		}
		// Convert Array to JSON String
		$conferancesJSON = json_encode($conferencesArray);
		return($conferancesJSON);
		//return($conferencesArray);
	}
    
    function deleteSpeaker(){
    
        if(!$this->exist()){
            return false;
        }
        // query to insert record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                full_name_speaker='".$this->full_name_speaker."'";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            //$this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }

    function updateSpeaker(){
    
        if(!$this->exist()){
            return false;
        }
        // query to insert record
        $query = "UPDATE 
        " . $this->table_name . "
        SET
            full_name_speaker=:full_name_speaker
        WHERE
            id_speaker='".$this->id_speaker."'";
    
        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
		$this->id_speaker=htmlspecialchars(strip_tags($this->id_speaker));
		$this->full_name_speaker=htmlspecialchars(strip_tags($this->full_name_speaker));
    
        // bind values
        $stmt->bindParam(":full_name_speaker", $this->full_name_speaker);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }


    function getIdByName()
    {
        $query = "SELECT id_speaker FROM " . $this->table_name . " WHERE full_name_speaker = '" . $this->full_name_speaker."'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_speaker'];
    }

}