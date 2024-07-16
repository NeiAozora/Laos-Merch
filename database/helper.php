<?php

// Function to check if a database exists
function databaseExists($dbh, $dbName) {
    $stmt = $dbh->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbName");
    $stmt->bindParam(':dbName', $dbName, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}




// Connect to MySQL server without selecting a specific database
$dbh = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the database exists
$existingDb = databaseExists($dbh, DB_NAME);

if (!$existingDb) {
    // Database does not exist, prompt to create
    echo "Database '" . DB_NAME . "' does not exist. Do you want to create it? (Y/N): ";
    $input = trim(fgets(STDIN));

    if (strtolower($input) === 'y') {
        // Create the database
        $dbh->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        echo "Database '" . DB_NAME . "' created successfully.\n";
    } else {
        die("Database creation aborted.\n");
    }
}

// Select the database
$dbh->exec("USE " . DB_NAME);

