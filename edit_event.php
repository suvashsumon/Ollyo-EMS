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

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = $_GET['id'];
} else {
    header("Location: dashboard.php");
    exit;
}

$event_details = $event->getEventById($event_id);

if (!$event_details || $event_details['created_by'] != $_SESSION['user_id']) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $capacity = $_POST['capacity'];

    if ($event->updateEvent($event_id, $name, $description, $date, $location, $capacity)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error_message = "Failed to update the event. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Event - Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
include_once './common/navbar.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Edit Event</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($event_details['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($event_details['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Event Date</label>
            <input type="datetime-local" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($event_details['date'])); ?>" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($event_details['location']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($event_details['capacity']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
