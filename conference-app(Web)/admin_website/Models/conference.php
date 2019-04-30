<?php

class Conference
{

    // database connection and table name
    /**
     * @var PDO
     */

    private $conn;
    private $table_name = "conferences";

    public $id_conf;
    public $email_admin;
    public $full_name_conf;
    public $short_name_conf;
    public $email_conf;
    public $website;
    public $created_conf;
    public $description;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function addConference()
    {
        if ($this->exist()) {
            return false;
        }
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET email_admin=:email_admin,
         full_name_conf=:full_name_conf, short_name_conf=:short_name_conf, 
         email_conf=:email_conf, website=:website,created_conf=:created_conf,description=:description";

        // prepare query
        $stmt = $this->conn->prepare($query);

        $this->email_admin = htmlspecialchars(strip_tags($this->email_admin));
        $this->full_name_conf = htmlspecialchars(strip_tags($this->full_name_conf));
        $this->short_name_conf = htmlspecialchars(strip_tags($this->short_name_conf));
        $this->email_conf = htmlspecialchars(strip_tags($this->email_conf));
        $this->website = htmlspecialchars(strip_tags($this->website));
        $this->created_conf = htmlspecialchars(strip_tags($this->created_conf));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // bind values
        // $stmt->bindParam(":id_conf", $this->id_conf);
        $stmt->bindParam(":email_admin", $this->email_admin);
        $stmt->bindParam(":full_name_conf", $this->full_name_conf);
        $stmt->bindParam(":short_name_conf", $this->short_name_conf);
        $stmt->bindParam(":email_conf", $this->email_conf);
        $stmt->bindParam(":website", $this->website);
        $stmt->bindParam(":created_conf", $this->created_conf);
        $stmt->bindParam(":description", $this->description);

        // execute query
        if ($stmt->execute()) {
            $this->id_conf = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    // login user
    /*function login(){
        // select all query
        $query = "SELECT
                     `nom`, `password`, `created`, `prenom`, `email`, `gsm`
                FROM
                    " . $this->table_name . " 
                WHERE
                    email='".$this->email."' AND password='".$this->password."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }*/
    function exist()
    {
        $query = "SELECT * FROM conferences WHERE conferences.SHORT_NAME_CONF ='" . $this->short_name_conf . "' 
        and conferences.EMAIL_ADMIN ='" . $this->email_admin . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*  public $id_conf;
    public $email_admin;
    public $full_name_conf;
    public $short_name_conf;
    public $email_conf;
    public $website;
    public $created_conf;
    public $description; */
    function getConference()
    {
        $conferencesArray = [];
        $query = "SELECT
                     `id_conf`, `full_name_conf`, `email_conf`, `website`, `created_conf`, `description`
                FROM
                    " . $this->table_name . " 
                WHERE
                    short_name_conf='" . $this->short_name_conf . "' AND email_admin='" . $this->email_admin . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($conferencesArray, [
                'id_conf' => $row['id_conf'],
                'full_name_conf' => $row['full_name_conf'],
                'email_conf' => $row['email_conf'],
                'website' => $row['website'],
                'created_conf' => $row['created_conf'],
                'description' => $row['description']
            ]);
        }
        // Convert Array to JSON String
        $conferancesJSON = json_encode($conferencesArray);
        return ($conferancesJSON);
        //return($conferencesArray);
    }

    function getIDConference()
    {
        $query = "SELECT
                     *
                FROM
                    " . $this->table_name . " 
                WHERE
                    short_name_conf='" . $this->short_name_conf . "' AND email_admin='" . $this->email_admin . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id_conf = $row[strtoupper('ID_CONF')];
        return (true);
        //return($conferencesArray);
    }

    function getAllConference()
    {

        $conferencesArray = [];
        $query = "SELECT * ,users.FIRST_NAME,users.LAST_NAME FROM " . $this->table_name . ", users
        where users.email_admin = " . $this->table_name . ".email_admin";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($conferencesArray, [
                'id_conf' => $row[strtoupper('ID_CONF')],
                'admin' => array(
                    'email_admin' => $row[strtoupper('email_admin')],
                    'name_admin' => $row['FIRST_NAME'] . " " . $row['LAST_NAME']
                ),
                'full_name_conf' => $row[strtoupper('full_name_conf')],
                'short_name_conf' => $row[strtoupper('short_name_conf')],
                'email_conf' => $row[strtoupper('email_conf')],
                'website' => $row[strtoupper('website')],
                'created_conf' => $row[strtoupper('created_conf')],
                'description' => $row[strtoupper('description')]
            ]);
        }
        // Convert Array to JSON String
        $conferancesJSON = json_encode($conferencesArray);
        return ($conferancesJSON);
        //return($conferencesArray);
    }

    function deleteConference()
    {

        if (!$this->exist()) {
            return false;
        }
        // query to insert record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                short_name_conf='" . $this->short_name_conf . "' AND email_admin='" . $this->email_admin . "'";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // execute query
        if ($stmt->execute()) {
            //$this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;

    }

    function updateConference()
    {

        if (!$this->exist()) {
            return false;
        }
        // query to insert record
        $query = "UPDATE  " . $this->table_name . "
        SET
            id_conf=:id_conf, full_name_conf=:full_name_conf, 
            email_conf=:email_conf, website=:website, created_conf=:created_conf, description=:description
        WHERE
            short_name_conf='" . $this->short_name_conf . "' AND email_admin='" . $this->email_admin . "'";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id_conf = htmlspecialchars(strip_tags($this->id_conf));
        $this->email_admin = htmlspecialchars(strip_tags($this->email_admin));
        $this->full_name_conf = htmlspecialchars(strip_tags($this->full_name_conf));
        $this->short_name_conf = htmlspecialchars(strip_tags($this->short_name_conf));
        $this->email_conf = htmlspecialchars(strip_tags($this->email_conf));
        $this->website = htmlspecialchars(strip_tags($this->website));
        $this->created_conf = htmlspecialchars(strip_tags($this->created_conf));
        $this->description = htmlspecialchars(strip_tags($this->description));

        //crée instance de l edition 

        // bind values
        $stmt->bindParam(":id_conf", $this->id_conf);
        $stmt->bindParam(":full_name_conf", $this->full_name_conf);
        $stmt->bindParam(":email_conf", $this->email_conf);
        $stmt->bindParam(":website", $this->website);
        $stmt->bindParam(":created_conf", $this->created_conf);
        $stmt->bindParam(":description", $this->description);

        // execute query
        if ($stmt->execute()) {
            //$this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;

    }

    public function getLastEdition()
    {
        $query = "SELECT max(ID_EDITION) as last_edition from conf_date_loc where ID_CONF=" . $this->id_conf;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['last_edition'];

    }

}