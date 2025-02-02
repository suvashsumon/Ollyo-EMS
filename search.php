<?php
require_once 'config/Database.php';
require_once 'classes/Search.php';
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$searchResults = [];
if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $search = new Search();
    $searchResults = $search->searchAll($query);
}

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include_once './common/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Search</h2>

    <!-- Search Form -->
    <form method="GET" action="search.php" class="mb-3" id="searchForm">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Search events, users, or event registrations..." required id="searchQuery">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Results Table -->
    <h4 id="searchResultsTitle" style="display:none;"></h4>
    <table class="table table-striped mt-3" id="resultsTable" style="display:none;">
        <thead class="table-dark">
            <tr>
                <th>Type</th>
                <th>Information</th>
            </tr>
        </thead>
        <tbody id="resultsBody">
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#searchForm').submit(function(e) {
            e.preventDefault();

            var query = $('#searchQuery').val();

            $.ajax({
                url: 'search.php?ajax=true',
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    console.log(response); // For debugging: Check the response
                    var data = response; // The response is already parsed JSON

                    var resultsTable = $('#resultsTable');
                    var resultsTitle = $('#searchResultsTitle');
                    var resultsBody = $('#resultsBody');

                    resultsTable.show();
                    resultsTitle.show().text('Search Results for "' + query + '"');
                    resultsBody.empty();

                    if (data.length > 0) {
                        data.forEach(function(result) {
                            var row = '<tr><td>' + ucfirst(result.type) + '</td><td>' + result.information + '</td></tr>';
                            resultsBody.append(row);
                        });
                    } else {
                        resultsBody.append('<tr><td colspan="2" class="text-center">No results found.</td></tr>');
                    }
                },
                error: function() {
                    alert('Error occurred while searching.');
                }
            });
        });
    });

    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>

</body>
</html>
