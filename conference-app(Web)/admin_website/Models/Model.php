<?php

class Model
{
    /**
     * @var mysqli
     */
    protected $conn;
    protected $table_name;

    public function __construct($db)
    {
        $this->conn = $db;
    }

}