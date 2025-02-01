<?php
require_once 'classes/Event.php';

if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die("Invalid event ID.");
}

$eventObj = new Event();
$event = $eventObj->getEventById($_GET['event_id']);

if (!$event) {
    die("Event not found.");
}

$remaining_capacity = $event['capacity'] - $event['registered'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center mb-4">Event Details</h2>
    
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>Event Name</th>
                <td><?php echo htmlspecialchars($event['name']); ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo htmlspecialchars($event['description']); ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo date("F j, Y", strtotime($event['date'])); ?></td>
            </tr>
            <tr>
                <th>Location</th>
                <td><?php echo htmlspecialchars($event['location']); ?></td>
            </tr>
            <tr>
                <th>Capacity</th>
                <td><?php echo htmlspecialchars($event['capacity']); ?></td>
            </tr>
            <tr>
                <th>Remaining Capacity</th>
                <td><?php echo max(0, $remaining_capacity); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="text-center">
        <a href="index.php" class="btn btn-secondary">Back</a>
        <a href="register_event.php?event_id=<?php echo $event['id']; ?>" 
           class="btn btn-success <?php echo ($remaining_capacity <= 0) ? 'disabled' : ''; ?>">
           Register
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
