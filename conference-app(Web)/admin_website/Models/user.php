<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    //public $id;
    public $created_admin;
    public $email_admin;
    public $first_name;
	public $last_name;
	public $pwd;
	public $tel;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // signup user
    function addUser(){
    
        if($this->exist()){
            return false;
        }
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                created_admin=:created_admin, email_admin=:email_admin, first_name=:first_name, last_name=:last_name, pwd=:pwd, tel=:tel";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->created_admin=htmlspecialchars(strip_tags($this->created_admin));
        $this->email_admin=htmlspecialchars(strip_tags($this->email_admin));
        $this->first_name=htmlspecialchars(strip_tags($this->first_name));
		$this->last_name=htmlspecialchars(strip_tags($this->last_name));
		$this->pwd=htmlspecialchars(strip_tags($this->pwd));
		$this->tel=htmlspecialchars(strip_tags($this->tel));
    
        // bind values
        $stmt->bindParam(":created_admin", $this->created_admin);
        $stmt->bindParam(":email_admin", $this->email_admin);
        $stmt->bindParam(":first_name", $this->first_name);
		$stmt->bindParam(":last_name", $this->last_name);
		$stmt->bindParam(":pwd", $this->pwd);
		$stmt->bindParam(":tel", $this->tel);
    
        // execute query
        if($stmt->execute()){
            //$this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }
    // login user
    function login(){
        // select all query
        $query = "SELECT
                     email_admin, pwd, first_name , last_name,tel
                FROM
                    " . $this->table_name . " 
                WHERE
                    email_admin='".$this->email_admin."' AND pwd='".$this->pwd."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }
    function exist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                email_admin='".$this->email_admin."'";
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

    /*public $created_admin;
    public $email_admin;
    public $first_name;
	public $last_name;
	public $pwd;
	public $tel; */
    function getUser(){
		$conferencesArray = [];
		$query = "SELECT
                     `created_admin`, `first_name`, `last_name`, `pwd`, `tel`
                FROM
                    " . $this->table_name . " 
                WHERE
                    email_admin='".$this->email_admin."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			array_push($conferencesArray, [
			'created_admin'   => $row['created_admin'],
			'first_name' => $row['first_name'],
			'last_name' => $row['last_name'],
			'pwd' => $row['pwd'],
			'tel' => $row['tel']
			]);
		}
		// Convert Array to JSON String
		$conferancesJSON = json_encode($conferencesArray);
		return($conferancesJSON);
		//return($conferencesArray);
	}
	function getAllUsers(){
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
                'created_admin'   => $row['created_admin'],
                'email_admin'   => $row['email_admin'],
			    'first_name' => $row['first_name'],
			    'last_name' => $row['last_name'],
			    'pwd' => $row['pwd'],
			    'tel' => $row['tel']
			]);
		}
		// Convert Array to JSON String
		$conferancesJSON = json_encode($conferencesArray);
		return($conferancesJSON);
		//return($conferencesArray);
	}
    
    function deleteUser(){
    
        if(!$this->exist()){
            return false;
        }
        // query to insert record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                email_admin='".$this->email_admin."'";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            //$this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }

    function updateUser(){
    
        if(!$this->exist()){
            return false;
        }
        // query to insert record
        $query = "UPDATE 
        " . $this->table_name . "
        SET
            created_admin=:created_admin,  first_name=:first_name, last_name=:last_name, pwd=:pwd, tel=:tel
        WHERE
            email_admin='".$this->email_admin."'";
    
        // prepare query
        $stmt = $this->conn->prepare($query);

           // sanitize
           $this->created_admin=htmlspecialchars(strip_tags($this->created_admin));
           $this->email_admin=htmlspecialchars(strip_tags($this->email_admin));
           $this->first_name=htmlspecialchars(strip_tags($this->first_name));
           $this->last_name=htmlspecialchars(strip_tags($this->last_name));
           $this->pwd=htmlspecialchars(strip_tags($this->pwd));
           $this->tel=htmlspecialchars(strip_tags($this->tel));
       
           // bind values
           $stmt->bindParam(":created_admin", $this->created_admin);
           $stmt->bindParam(":first_name", $this->first_name);
           $stmt->bindParam(":last_name", $this->last_name);
           $stmt->bindParam(":pwd", $this->pwd);
           $stmt->bindParam(":tel", $this->tel);
    
        // execute query
        if($stmt->execute()){
            //$this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }
}