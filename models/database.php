<?php
class Database
{
    private $host, $user, $pass, $database;
    public $conn;

    function __construct($host_, $user_, $pass_, $database_)
    {
        $this->host = $host_;
        $this->user = $user_;
        $this->pass = $pass_;
        $this->database = $database_;

        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } else {
            return true;
        }
    }
}


