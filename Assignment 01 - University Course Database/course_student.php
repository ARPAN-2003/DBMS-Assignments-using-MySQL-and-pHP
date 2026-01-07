<?php
    $servername = "localhost:3308";
    $username = "root";
    $password = "";
    $database_name = "UNIVERSITY";

    $conn = mysqli_connect($servername, $username, $password, $database_name);

    if (!$conn) {
        $success = false;
        $message = "Database connection failed: " . mysqli_connect_error();
        $table_data = array();
    } else {
        $studentId = isset($_POST['studentId']) ? mysqli_real_escape_string($conn, $_POST['studentId']) : '';
        $courseId = isset($_POST['courseId']) ? mysqli_real_escape_string($conn, $_POST['courseId']) : '';
        $grade = isset($_POST['grade']) ? mysqli_real_escape_string($conn, $_POST['grade']) : '';

        $insert_query = "INSERT INTO Course_Student (StudentID, CourseID, Grade) VALUES ('$studentId', '$courseId', '$grade')";

        if (mysqli_query($conn, $insert_query)) {
            $success = true;
            $message = "Course-Student Form data is successfully stored in Course_Student Database. Thank You.";
        } else {
            $success = false;
            $message = "Error inserting data: " . mysqli_error($conn);
        }

        $select_query = "SELECT * FROM Course_Student ORDER BY StudentID";
        $result = mysqli_query($conn, $select_query);
        $table_data = array();
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $table_data[] = $row;
            }
        }
        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course-Student Form Submission Result</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            width: 100%;
        }

        .message-box {
            background: #2d2d2d;
            border-radius: 12px 30px;
            padding: 36px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            margin-bottom: 20px;
        }

        .success-message {
            border-left: 7px solid #6ae670;
        }

        .success-message .icon {
            color: #6ae670;
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .success-message h2 {
            color: #6ae670;
            font-size: 1.7rem;
            margin-bottom: 15px;
        }

        .success-message p {
            color: #b0bec5;
            font-size: 1rem;
            line-height: 1.6;
        }

        .failure-message {
            border-left: 7px solid #ef5350;
        }

        .failure-message .icon {
            color: #ef5350;
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .failure-message h2 {
            color: #e57373;
            font-size: 1.7rem;
            margin-bottom: 15px;
        }

        .failure-message p {
            color: #b0bec5;
            font-size: 1rem;
            line-height: 1.6;
        }

        .spacer {
            height: 30px;
        }

        .data-display-section {
            background: #2d2d2d;
            border-radius: 30px 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .data-display-section h3 {
            color: #64b5f6;
            font-size: 1.4rem;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #64b5f6;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
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
        }

        table td {
            padding: 12px;
            border: 1.7px solid #64b5f6;
            color: #e0e0e0;
            text-align: center;
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
            gap: 35px;
            justify-content: center;
            margin-top: 30px;
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

        .btn-continue {
            background: #3a3a3a;
            color: #b4dffa;
            border: 2px solid #555;
        }

        .btn-continue:hover {
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

        @media (max-width: 600px) {
            .message-box,
            .data-display-section {
                padding: 25px;
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
        <!-- MESSAGE -->
        <div class="message-box <?php echo $success ? 'success-message' : 'failure-message'; ?>">
            <div class="icon"><?php echo $success ? '✓' : '✗'; ?></div>
            <h2><?php echo $success ? 'Success!' : 'Error!'; ?></h2>
            <p><?php echo htmlspecialchars($message); ?></p>
        </div>

        <!-- SPACER -->
        <div class="spacer"></div>

        <!-- DATA DISPLAY SECTION -->
        <div class="data-display-section">
            <h3>📊 Inserted Data - Course_Student Table</h3>

            <table>
                <thead>
                    <tr>
                        <th>Student's ID</th>
                        <th>Course ID</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if(count($table_data) > 0) {
                            foreach($table_data as $row) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['StudentID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['CourseID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Grade']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='no-data'>No records found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="button-group">
            <button class="btn-continue" onclick="if(confirm('Are you sure to go back to the Home page?')) { window.location.href='Home.html'; }">🏡🔙 Back to Home Page &nbsp;→</button>
            <button class="btn-back" onclick="window.location.href='course_student.html'">↻ &nbsp; Insert another data</button>
        </div>
    </div>
</body>
</html>