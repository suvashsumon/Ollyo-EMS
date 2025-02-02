<?php
require_once __DIR__ . '/config/Database.php';

class TableCreator {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createTables() {
        $sql = "
        ALTER TABLE event_registrations 
        ADD CONSTRAINT unique_event_registration UNIQUE (event_id, guest_email);
        ";

        try {
            $this->conn->exec($sql);
            echo "Tables created successfully.";
        } catch (PDOException $e) {
            echo "Error creating tables: " . $e->getMessage();
        }
    }
}

$creator = new TableCreator();
$creator->createTables();
?>
