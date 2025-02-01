<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/Event.php';

session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Invalid event ID.');
}

$auth = new Auth();
$user = $auth->getUserById($_SESSION['user_id']);
if($user['role'] !== 'admin') {
    die('You do not have permission to access this page.');
}

$event_id = $_GET['id'];

try {
    $event = new Event();
    $eventDetails = $event->getEventById($event_id);

    if (!$eventDetails) {
        die('Event not found.');
    }

    $eventRegistrations = $event->getRegisteredUsers($event_id);

    if (!$eventRegistrations) {
        die('No registered users found for this event.');
    }

    // Prepare CSV file
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="event_' . $event_id . '_registrations.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Name', 'Email']);

    foreach ($eventRegistrations as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
