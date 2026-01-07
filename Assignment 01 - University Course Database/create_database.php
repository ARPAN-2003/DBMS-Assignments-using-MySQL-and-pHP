<?php
    header('Content-Type: application/json');

    // Database connection
    $servername = "localhost:3308";
    $username = "root";
    $password = "";
    $database_name = "UNIVERSITY";

    // Connect without database name first
    $conn = mysqli_connect($servername, $username, $password);

    if (!$conn) {
        echo json_encode([
            'success' => false,
            'message' => 'Connection failed: ' . mysqli_connect_error()
        ]);
        exit();
    }

    // Check if database already exists
    $check_db_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . mysqli_real_escape_string($conn, $database_name) . "'";
    $db_result = mysqli_query($conn, $check_db_query);

    if (!$db_result) {
        echo json_encode([
            'success' => false,
            'message' => 'Database query failed: ' . mysqli_error($conn)
        ]);
        mysqli_close($conn);
        exit();
    }

    if (mysqli_num_rows($db_result) > 0) {
        // Database already exists
        echo json_encode([
            'success' => false,
            'message' => 'Database already exists! Please fill the Forms...'
        ]);
        mysqli_close($conn);
        exit();
    }

    // Create database only
    $create_db_query = "CREATE DATABASE " . $database_name;

    if (mysqli_query($conn, $create_db_query)) {
        // Database created successfully
        echo json_encode([
            'success' => true,
            'message' => 'Database created successfully! Now click "Create Form" to create tables and insert data...'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error creating database: ' . mysqli_error($conn)
        ]);
    }

    mysqli_close($conn);
?>