<?php
include_once("config/config.php");
include_once(DIR_URL . "config/database.php");

// Read and execute the SQL update
$sql = file_get_contents('update_schema.sql');

// Split by statement to handle multiple queries
$statements = explode(';', $sql);
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        if ($conn->query($statement)) {
            echo "Executed: " . substr($statement, 0, 50) . "...\n";
        } else {
            echo "Error executing: " . substr($statement, 0, 50) . "...\n";
            echo "Error: " . $conn->error . "\n";
        }
    }
}

echo "Schema update completed.\n";
