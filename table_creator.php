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
        ALTER TABLE event_registrations ADD CONSTRAINT unique_guest_email UNIQUE (guest_email);

        UPDATE users SET role = 'admin' WHERE email = 'admin@admin.com';
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
