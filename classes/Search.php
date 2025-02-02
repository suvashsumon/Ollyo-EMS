<?php
require_once 'config/Database.php';

class Search {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function searchEvents($query) {
        $sql = "SELECT 'event' AS type, 
                       CONCAT('Event: ', name, ' | Location: ', location, ' | Date: ', DATE_FORMAT(date, '%M %d, %Y %h:%i %p')) AS information
                FROM events
                WHERE name LIKE :query OR description LIKE :query OR location LIKE :query";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($query) {
        $sql = "SELECT 'user' AS type, 
                       CONCAT('User: ', name, ' | Email: ', email, ' | Role: ', role) AS information
                FROM users
                WHERE name LIKE :query OR email LIKE :query";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchEventRegistrations($query) {
        $sql = "SELECT 'event_attendee' AS type, 
                       CONCAT('Guest: ', event_registrations.guest_name, ' | Email: ', event_registrations.guest_email, ' | Event: ', events.name) AS information
                FROM event_registrations
                JOIN events ON event_registrations.event_id = events.id
                WHERE event_registrations.guest_name LIKE :query OR event_registrations.guest_email LIKE :query OR events.name LIKE :query";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function searchAll($query) {
        $events = $this->searchEvents($query);
        $users = $this->searchUsers($query);
        $eventRegistrations = $this->searchEventRegistrations($query);
        return array_merge($events, $users, $eventRegistrations);
    }
}
?>
