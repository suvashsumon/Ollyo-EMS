<?php
session_start();

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Event.php';

$event = new Event();

$error_message = $success_message = '';

if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $event_details = $event->getEventById($event_id);

    if ($event_details) {

        if (isset($_POST['guest_name'], $_POST['guest_email'])) {
            if ($event->registerForEvent($event_id, null, $_POST['guest_name'], $_POST['guest_email'])) {
                $success_message = "You have successfully registered for the event as a guest!";
            } else {
                $error_message = "Failed to register as a guest or the event is full.";
            }
        }
    } else {
        $error_message = "Event not found.";
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
    <title>Register for Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Register for Event</h2>

    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($event_details) && $event_details): ?>
        <h4><?= htmlspecialchars($event_details['name']) ?></h4>
        <p><?= htmlspecialchars($event_details['description']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($event_details['date']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($event_details['location']) ?></p>
        
        <form method="POST">
            <div class="mb-3">
                <label for="guest_name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="guest_name" name="guest_name" required>
            </div>
            <div class="mb-3">
                <label for="guest_email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="guest_email" name="guest_email" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    <?php else: ?>
        <p>No event details found.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>