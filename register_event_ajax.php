<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Event.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['event_id'], $_POST['guest_name'], $_POST['guest_email'])) {
        echo json_encode(["error" => "All fields are required."]);
        exit;
    }

    $event_id = $_POST['event_id'];
    $guest_name = $_POST['guest_name'];
    $guest_email = $_POST['guest_email'];

    $event = new Event();
    
    $event_details = $event->getEventById($event_id);
    if (!$event_details) {
        echo json_encode(["error" => "Event not found."]);
        exit;
    }

    if ($event->registerForEvent($event_id, null, $guest_name, $guest_email)) {
        echo json_encode(["success" => "You have successfully registered as a guest!"]);
    } else {
        echo json_encode(["error" => "Failed to register. The event may be full."]);
    }
}
