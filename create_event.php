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

$error_message = $success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $capacity = isset($_POST['capacity']) ? (int)$_POST['capacity'] : 0;

    if (empty($name) || empty($description) || empty($date) || empty($location) || empty($capacity)) {
        $error_message = "Please fill in all fields.";
    } else {
        $currentDate = date('Y-m-d');
        if ($date < $currentDate) {
            $error_message = "Event date cannot be in the past.";
        } else {
            $created_by = $auth->getUserId();
            if ($event->createEvent($name, $description, $date, $location, $capacity, $created_by)) {
                $success_message = "Event created successfully!";
            } else {
                $error_message = "Failed to create the event. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Create New Event</h2>

    <!-- Display success or error message -->
    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <!-- Event creation form -->
    <form action="create_event.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Event Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Event Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Event Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>

        <div class="mb-3">
            <label for="capacity" class="form-label">Event Capacity</label>
            <input type="number" class="form-control" id="capacity" name="capacity" required min="1">
        </div>

        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
