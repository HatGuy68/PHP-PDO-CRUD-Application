<?php

class Database {
    private $DB_DSN = "mysql:host=localhost:3306;dbname=player_db";
    private $DB_USERNAME = "root";
    private $DB_PASSWORD = "";
    protected $conn;

    public function __construct()
    {
        try {
            $options = array(PDO::ATTR_PERSISTENT);
            $this->conn = new PDO($this->DB_DSN, $this->DB_USERNAME, $this->DB_PASSWORD, $options);
        } catch ( PDOException $e ) {
            echo 'Connection Error: '.$e->getMessage();
        }
    }
}

?>