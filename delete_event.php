<?php
session_start();

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/Event.php';

$auth = new Auth();
$event = new Event();

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = $_GET['id'];
    
    $event_details = $event->getEventById($event_id);
    
    if ($event_details && $event_details['created_by'] == $auth->getUserId()) {
        if ($event->deleteEvent($event_id)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Failed to delete the event. Please try again.";
        }
    } else {
        $error_message = "You are not authorized to delete this event.";
    }
} else {
    $error_message = "Invalid event ID.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Delete Event</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
