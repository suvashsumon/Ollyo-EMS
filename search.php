<?php
require_once 'classes/Search.php';
session_start();

$searchResults = [];

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    
    $search = new Search();
    $searchResults = $search->searchAll($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once './common/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Search</h2>

    <!-- Search Form -->
    <form method="GET" action="search.php" class="mb-3">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Search events, users, or event registrations..." required>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Results Table -->
    <?php if (isset($_GET['query'])): ?>
        <h4>Search Results for "<?php echo htmlspecialchars($_GET['query']); ?>"</h4>
        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Type</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($searchResults)): ?>
                    <?php foreach ($searchResults as $result): ?>
                        <tr>
                            <td><?php echo ucfirst($result['type']); ?></td>
                            <td><?php echo htmlspecialchars($result['information']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2" class="text-center">No results found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
