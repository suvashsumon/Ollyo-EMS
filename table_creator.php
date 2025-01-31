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
        CREATE TABLE `attendees` (
          `id` int NOT NULL AUTO_INCREMENT,
          `event_id` int DEFAULT NULL,
          `user_id` int DEFAULT NULL,
          `registered_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE `events` (
          `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `description` text,
          `date` datetime NOT NULL,
          `location` varchar(255) DEFAULT NULL,
          `capacity` int NOT NULL,
          `created_by` int DEFAULT NULL,
          `registered` int DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE `event_registrations` (
          `id` int NOT NULL AUTO_INCREMENT,
          `event_id` int NOT NULL,
          `user_id` int DEFAULT NULL,
          `guest_name` varchar(255) DEFAULT NULL,
          `guest_email` varchar(255) DEFAULT NULL,
          `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE `users` (
          `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `email` varchar(255) NOT NULL,
          `password` varchar(255) NOT NULL,
          `role` enum('admin','user') DEFAULT 'user',
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
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
