<?php
    header('Content-Type: application/json');

    // Database connection...
    $servername = "localhost:3308";
    $username = "root";
    $password = "";
    $database_name = "RESEARCH";

    $conn = mysqli_connect($servername, $username, $password, $database_name);

    if (!$conn) {
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed. Please check your database credentials.'
        ]);
        exit();
    }

    // Get table name from request
    $table_name = isset($_POST['table_name']) ? $_POST['table_name'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : 'create';

    if (empty($table_name)) {
        echo json_encode([
            'success' => false,
            'message' => 'Table name not provided.'
        ]);
        exit();
    }

    // Check if table exists
    $check_query = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . $database_name . "' AND TABLE_NAME = '" . $table_name . "'";

    $result = mysqli_query($conn, $check_query);
    $table_exists = (mysqli_num_rows($result) > 0);

    // Handle different actions
    if ($action === 'create') {
        // CREATE action - Check if table already exists
        if ($table_exists) {
            echo json_encode([
                'success' => false,
                'message' => 'Form already exists. Fill the Form to add more records...'
            ]);
        } else {
            // Table doesn't exist, Let's create the Table...
            $create_query = getCreateTableQuery($table_name);
            
            if (!empty($create_query)) {
                if (mysqli_query($conn, $create_query)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Form created successfully! You can now fill the form to add records.'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error creating table: ' . mysqli_error($conn)
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Unknown table type.'
                ]);
            }
        }
    } elseif ($action === 'check') {
        // CHECK action - Just return if table exists or not (no success/failure in traditional sense)
        echo json_encode([
            'table_exists' => $table_exists
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action.'
        ]);
    }

    mysqli_close($conn);

    // Function to get CREATE TABLE query based on table name
    function getCreateTableQuery($table_name) {
        switch($table_name) {
            case 'Office':
                return "CREATE TABLE Office (
                    OfficeID VARCHAR(50) PRIMARY KEY,
                    OfficeName VARCHAR(100) NOT NULL,
                    OfficeAddress VARCHAR(200) NOT NULL,
                    PhoneExtension VARCHAR(20) NOT NULL
                )";

            case 'Researcher':
                return "CREATE TABLE Researcher (
                    EmployeeID VARCHAR(50) PRIMARY KEY,
                    EmployeeName VARCHAR(100) NOT NULL,
                    OfficeID VARCHAR(50) NOT NULL,
                    FOREIGN KEY (OfficeID) REFERENCES Office(OfficeID)
                )";
            
            case 'Lab_Equipment':
                return "CREATE TABLE Lab_Equipment (
                    EquipmentID VARCHAR(50) PRIMARY KEY,
                    EquipmentName VARCHAR(100) NOT NULL,
                    CalibrationStandard VARCHAR(100) NOT NULL
                )";

            case 'Uses':
                return "CREATE TABLE Uses (
                    EmployeeID VARCHAR(50) NOT NULL,
                    EquipmentID VARCHAR(50) NOT NULL,
                    PRIMARY KEY (EmployeeID, EquipmentID),
                    FOREIGN KEY (EmployeeID) REFERENCES Researcher(EmployeeID),
                    FOREIGN KEY (EquipmentID) REFERENCES Lab_Equipment(EquipmentID)
                )";

            case 'Journal_Issue':
                return "CREATE TABLE Journal_Issue (
                    VolumeID VARCHAR(50) PRIMARY KEY,
                    JournalTitle VARCHAR(100) NOT NULL,
                    PublicationDate DATE NOT NULL,
                    JournalFormat VARCHAR(50) NOT NULL,
                    EditorInChief VARCHAR(50) NOT NULL,
                    FOREIGN KEY (EditorInChief) REFERENCES Researcher(EmployeeID)
                )";

            case 'Research_Paper':
                return "CREATE TABLE Research_Paper (
                    PaperID VARCHAR(50) PRIMARY KEY,
                    PaperName VARCHAR(50) NOT NULL,
                    VolumeID VARCHAR(50) NOT NULL,
                    FOREIGN KEY (VolumeID) REFERENCES Journal_Issue(VolumeID)
                )";

            case 'Writes':
                return "CREATE TABLE Writes (
                    EmployeeID VARCHAR(50) NOT NULL,
                    PaperID VARCHAR(50) NOT NULL,
                    PRIMARY KEY (EmployeeID, PaperID),
                    FOREIGN KEY (EmployeeID) REFERENCES Researcher(EmployeeID),
                    FOREIGN KEY (PaperID) REFERENCES Research_Paper(PaperID)
                )";

            case 'Leads':
                return "CREATE TABLE Leads (
                    PaperID VARCHAR(50) NOT NULL,
                    LeadAuthorID VARCHAR(50) NOT NULL,
                    PRIMARY KEY (PaperID, LeadAuthorID),
                    FOREIGN KEY (PaperID) REFERENCES Research_Paper(PaperID),
                    FOREIGN KEY (LeadAuthorID) REFERENCES Writes(EmployeeID)
                )";

            default:
                return "";
        }
    }
?>