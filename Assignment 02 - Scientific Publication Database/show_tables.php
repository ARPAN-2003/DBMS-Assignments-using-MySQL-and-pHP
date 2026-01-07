<?php
    // Database connection...
    $servername = "localhost:3308";
    $username = "root";
    $password = "";
    $database_name = "RESEARCH";

    $conn = mysqli_connect($servername, $username, $password, $database_name);

    if (!$conn) {
        echo "Connection failed: " . mysqli_connect_error();
        exit();
    }

    // Get table name from URL parameter
    $table_name = isset($_GET['table']) ? $_GET['table'] : '';

    // Validate table name (whitelist of allowed tables)
    $allowed_tables = array(
        'Office',
        'Researcher',
        'Lab_Equipment',
        'Uses',
        'Journal_Issue',
        'Research_Paper',
        'Writes',
        'Leads'
    );

    if (empty($table_name) || !in_array($table_name, $allowed_tables)) {
        echo "Invalid table name or table not specified.";
        exit();
    }

    // Check if table exists
    $check_query = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . $database_name . "' AND TABLE_NAME = '" . $table_name . "'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) === 0) {
        echo "Table does not exist.";
        exit();
    }

    // Get table structure (column names and types)
    $columns_query = "DESCRIBE " . $table_name;
    $columns_result = mysqli_query($conn, $columns_query);

    $columns = array();
    while ($col = mysqli_fetch_assoc($columns_result)) {
        $columns[] = $col['Field'];
    }

    // Fetch all data from the table
    $select_query = "SELECT * FROM " . $table_name;
    $result = mysqli_query($conn, $select_query);

    if (!$result) {
        echo "Error fetching data: " . mysqli_error($conn);
        exit();
    }

    $table_data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $table_data[] = $row;
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $table_name; ?> - Table Data</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8fc2f7 0%, #5da6f5 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 2rem;
            color: #2d2d2d;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.5);
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1rem;
            color: #2d2d2d;
        }

        .data-display-section {
            background: #2d2d2d;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin-bottom: 30px;
        }

        .data-display-section h3 {
            color: #64b5f6;
            font-size: 1.3rem;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #64b5f6;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            overflow-x: auto;
        }

        table thead {
            background: #353535;
            color: #64b5f6;
        }

        table th {
            padding: 12px;
            text-align: center;
            font-weight: 600;
            border: 1.7px solid #64b5f6;
            word-wrap: break-word;
        }

        table td {
            padding: 12px;
            border: 1.7px solid #64b5f6;
            color: #e0e0e0;
            text-align: center;
            word-wrap: break-word;
        }

        table tbody tr:nth-child(even) {
            background: #353535;
        }

        table tbody tr:nth-child(odd) {
            background: #3a3a3a;
        }

        table tbody tr:hover {
            background: #404040;
        }

        .no-data {
            text-align: center;
            color: #888;
            padding: 20px;
            font-size: 1rem;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        button {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-home {
            background: #3a3a3a;
            color: #b4dffa;
            border: 2px solid #555;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(58, 58, 58, 0.4);
            background: #444;
        }

        .btn-back {
            background: #3a3a3a;
            color: #e0e0e0;
            border: 2px solid #555;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(58, 58, 58, 0.4);
            background: #444;
            border-color: #666;
        }

        .record-count {
            text-align: center;
            color: #b0bec5;
            margin-top: 20px;
            font-size: 0.95rem;
        }

        @media (max-width: 600px) {
            .data-display-section {
                padding: 20px;
            }

            table th,
            table td {
                padding: 8px;
                font-size: 0.9rem;
            }

            button {
                padding: 12px 20px;
                font-size: 0.9rem;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 <?php echo $table_name; ?> Table</h1>
            <p>All records in this table</p>
        </div>

        <div class="data-display-section">
            <h3>📋 Table Data - <?php echo $table_name; ?></h3>
            
            <table>
                <thead>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <th><?php echo htmlspecialchars($column); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if(count($table_data) > 0) {
                            foreach($table_data as $row) {
                                echo "<tr>";
                                foreach ($columns as $column) {
                                    echo "<td>" . htmlspecialchars($row[$column] ?? '') . "</td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            $colspan = count($columns);
                            echo "<tr><td colspan='" . $colspan . "' class='no-data'>No records found in this table</td></tr>";
                        }
                    ?>
                </tbody>
            </table>

            <div class="record-count">
                Total Records: <strong><?php echo count($table_data); ?></strong>
            </div>
        </div>

        <div class="button-group">
            <button class="btn-home" onclick="window.location.href='Home.html'">← Back to Home</button>
            <button class="btn-back" onclick="history.back()">← Go Back</button>
        </div>
    </div>
</body>
</html>