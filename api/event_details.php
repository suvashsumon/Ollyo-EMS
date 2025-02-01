<?php
header("Content-Type: application/json");
require_once './../classes/Event.php';

$event_id = $_GET['event_id'] ?? null;
if (!$event_id || !is_numeric($event_id)) {
    echo json_encode(["error" => "Invalid event ID."]);
    exit;
}

$eventObj = new Event();
$event = $eventObj->getEventById($event_id);

if ($event) {
    $event['remaining_capacity'] = max(0, $event['capacity'] - $event['registered']);
    echo json_encode($event, JSON_PRETTY_PRINT);
} else {
    echo json_encode(["error" => "Event not found."]);
}
