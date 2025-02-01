<?php
session_start();
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Event.php';

$event = new Event();
$error_message = $success_message = '';
$event_details = null; // Initialize the variable

if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $event_details = $event->getEventById($event_id);
} else {
    die(json_encode(["error" => "Invalid event ID."]));
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
    <h2 class="mb-4">Register for Event</h2>

    <div id="response-message"></div> <!-- Success/Error messages appear here -->

    <div class="row">
        <!-- Left Column: Event Details Table -->
        <div class="col-md-6">
            <?php if ($event_details): ?>
                <h4 class="mt-4">Event Details</h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Event Name</th>
                            <td><?= htmlspecialchars($event_details['name']) ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?= htmlspecialchars($event_details['description']) ?></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?= htmlspecialchars($event_details['date']) ?></td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td><?= htmlspecialchars($event_details['location']) ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert alert-warning">No event details found.</p>
            <?php endif; ?>
        </div>

        <!-- Right Column: Registration Form -->
        <div class="col-md-6">
            <?php if ($event_details): ?>
                <h4 class="mt-4">Guest Registration</h4>
                <form id="register-form">
                    <input type="hidden" name="event_id" value="<?= $event_id ?>">

                    <div class="mb-3">
                        <label for="guest_name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="guest_name" name="guest_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="guest_email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="guest_email" name="guest_email" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="register-btn">Register</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#register-form").submit(function(event) {
        event.preventDefault();
        
        $.ajax({
            url: "register_event_ajax.php", 
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#response-message").html('<div class="alert alert-success">' + response.success + '</div>');
                    $("#register-btn").prop("disabled", true);
                } else {
                    $("#response-message").html('<div class="alert alert-danger">' + response.error + '</div>');
                }
            },
            error: function() {
                $("#response-message").html('<div class="alert alert-danger">Something went wrong!</div>');
            }
        });
    });
});
</script>

</body>
</html>
