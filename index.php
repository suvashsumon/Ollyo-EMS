<?php
require_once 'classes/Event.php';
session_start();

$eventObj = new Event();
$events = $eventObj->getEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>

<?php
include_once './common/navbar.php';
?>

<!-- Event Listing -->
<div class="container mt-4">
    <h2 class="text-center">Upcoming Events</h2>
    <table id="eventsTable" class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Event Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Location</th>
                <th>Remaining Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['name']); ?></td>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                        <td><?php echo date("F j, Y h:i A", strtotime($event['date'])); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td><?php echo htmlspecialchars($event['remaining_capacity']); ?></td>
                        <td>
                            <a href="view_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-primary btn-sm">View</a>
                            <!-- Disable register button if remaining capacity is 0 -->
                            <?php if ($event['remaining_capacity'] > 0): ?>
                                <a href="register_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-success btn-sm">Register</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No events found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#eventsTable').DataTable();
    });
</script>
</body>
</html>
