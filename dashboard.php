<?php
require_once 'config/Database.php';
require_once 'classes/Auth.php';
require_once 'classes/Event.php';

session_start();

$auth = new Auth();
$event = new Event();

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = $auth->getUserById($_SESSION['user_id']);
$events = $event->getEvents($user);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>

<?php
include_once './common/navbar.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    </div>

    <h3 class="mt-4">Manage Events</h3>
    <a href="create_event.php" class="btn btn-success mb-3">Create New Event</a>

    <table id="eventsTable" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Event Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $index => $event): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($event['name']); ?></td>
                    <td><?php echo htmlspecialchars($event['description']); ?></td>
                    <td><?php echo htmlspecialchars($event['date']); ?></td>
                    <td>
                        <a href="view_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <?php if ($user['role'] === 'admin'): ?>
                            <a href="export_event.php?id=<?php echo $event['id']; ?>" class="btn btn-warning btn-sm">Export</a>
                        <?php endif; ?>
                        <a href="delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#eventsTable').DataTable();
    });
</script>
</body>
</html>
