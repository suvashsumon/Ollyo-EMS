<?php

require_once __DIR__ . '/config/Database.php';

$conn = new Database();
if($conn->connect()) {
    echo "Connected to the database";
} else {
    echo "Failed to connect to the database";
}
