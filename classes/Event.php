<?php
require_once __DIR__ . '/../config/Database.php';

class Event {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createEvent($name, $description, $date, $location, $capacity, $created_by) {
        $sql = "INSERT INTO events (name, description, date, location, capacity, created_by, registered)
                VALUES (:name, :description, :date, :location, :capacity, :created_by, 0)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'description' => $description,
            'date' => $date,
            'location' => $location,
            'capacity' => $capacity,
            'created_by' => $created_by
        ]);
    }

    public function getEvents($user=null) {
        $user_id = $user ? $user['id'] : null;
        $user_role = $user ? $user['role'] : null;
        if ($user_id && $user_role === 'user') {
            $sql = "SELECT events.*,
                           (events.capacity - IFNULL(COUNT(event_registrations.id), 0)) AS remaining_capacity
                    FROM events
                    LEFT JOIN event_registrations ON events.id = event_registrations.event_id
                    WHERE events.created_by = :user_id
                    GROUP BY events.id
                    ORDER BY events.date ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
        } else {
            $sql = "SELECT events.*,
                           (events.capacity - IFNULL(COUNT(event_registrations.id), 0)) AS remaining_capacity
                    FROM events
                    LEFT JOIN event_registrations ON events.id = event_registrations.event_id
                    GROUP BY events.id
                    ORDER BY events.date ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getEventById($event_id) {
        $sql = "SELECT * FROM events WHERE id = :event_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['event_id' => $event_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  
    public function updateEvent($id, $name, $description, $date, $location, $capacity) {
        $sql = "UPDATE events SET name = :name, description = :description, date = :date, location = :location, capacity = :capacity WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'date' => $date,
            'location' => $location,
            'capacity' => $capacity
        ]);
    }

    public function deleteEvent($id) {
        $sql = "DELETE FROM events WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function registerForEvent($event_id, $user_id, $guest_name = null, $guest_email = null) {
        $event_details = $this->getEventById($event_id);
        if ($event_details['registered'] < $event_details['capacity']) {
            if ($user_id) {
                $sql = "INSERT INTO event_registrations (event_id, user_id) VALUES (:event_id, :user_id)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute(['event_id' => $event_id, 'user_id' => $user_id]);
            }
            elseif ($guest_name && $guest_email) {
                $sql = "INSERT INTO event_registrations (event_id, guest_name, guest_email) VALUES (:event_id, :guest_name, :guest_email)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute(['event_id' => $event_id, 'guest_name' => $guest_name, 'guest_email' => $guest_email]);
            }
    
            $this->updateRegisteredCount($event_id);
    
            return true;
        } else {
            return false;
        }
    }

    private function updateRegisteredCount($event_id) {
        $sql = "UPDATE events SET registered = registered + 1 WHERE id = :event_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['event_id' => $event_id]);
    }

    public function getRegisteredUsers($event_id=null) {
        if($event_id==null) {
            return [];
        }

        $sql = "SELECT guest_name, guest_email FROM event_registrations
                WHERE event_registrations.event_id = :event_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['event_id' => $event_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
