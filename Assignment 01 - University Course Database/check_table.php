<?php
    header('Content-Type: application/json');

    // Database connection...
    $servername = "localhost:3308";
    $username = "root";
    $password = "";
    $database_name = "UNIVERSITY";

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
            case 'Academic_Department':
                return "CREATE TABLE Academic_Department (
                    DeptID VARCHAR(50) PRIMARY KEY,
                    DeptName VARCHAR(100) NOT NULL,
                    MainOfficeLocation VARCHAR(200) NOT NULL
                )";
            
            case 'Course':
                return "CREATE TABLE Course (
                    CourseID VARCHAR(50) PRIMARY KEY,
                    CourseTitle VARCHAR(100) NOT NULL,
                    OfferingYear INT NOT NULL,
                    Duration INT NOT NULL,
                    SyllabusOutline TEXT,
                    DeptID VARCHAR(50),
                    FOREIGN KEY (DeptID) REFERENCES Academic_Department(DeptID)
                )";
            
            case 'Subject_Area':
                return "CREATE TABLE Subject_Area (
                    AreaID VARCHAR(50) PRIMARY KEY,
                    AreaName VARCHAR(100) NOT NULL UNIQUE
                )";
            
            case 'Course_SubjectArea':
                return "CREATE TABLE Course_SubjectArea (
                    CourseID VARCHAR(50) NOT NULL,
                    AreaID VARCHAR(50) NOT NULL,
                    PRIMARY KEY (CourseID, AreaID),
                    FOREIGN KEY (CourseID) REFERENCES Course(CourseID),
                    FOREIGN KEY (AreaID) REFERENCES Subject_Area(AreaID)
                )";
            
            case 'Person':
                return "CREATE TABLE Person (
                    PersonID VARCHAR(50) PRIMARY KEY,
                    PersonName VARCHAR(100) NOT NULL,
                    DateOfBirth DATE NOT NULL
                )";
            
            case 'Student':
                return "CREATE TABLE Student (
                    StudentID VARCHAR(50) PRIMARY KEY,
                    PersonID VARCHAR(50) NOT NULL UNIQUE,
                    FOREIGN KEY (PersonID) REFERENCES Person(PersonID)
                )";
            
            case 'Instructor':
                return "CREATE TABLE Instructor (
                    InstructorID VARCHAR(50) PRIMARY KEY,
                    PersonID VARCHAR(50) NOT NULL UNIQUE,
                    FOREIGN KEY (PersonID) REFERENCES Person(PersonID)
                )";

            case 'Enrolls':
                return "CREATE TABLE Enrolls (
                    CourseID VARCHAR(50) NOT NULL,
                    PersonID VARCHAR(50) NOT NULL,
                    PRIMARY KEY (CourseID, PersonID),
                    FOREIGN KEY (CourseID) REFERENCES Course(CourseID),
                    FOREIGN KEY (PersonID) REFERENCES Person(PersonID)
                )";
            
            case 'Course_Instructor':
                return "CREATE TABLE Course_Instructor (
                    CourseID VARCHAR(50) NOT NULL,
                    InstructorID VARCHAR(50) NOT NULL,
                    PRIMARY KEY (CourseID, InstructorID),
                    FOREIGN KEY (CourseID) REFERENCES Course(CourseID),
                    FOREIGN KEY (InstructorID) REFERENCES Instructor(InstructorID)
                )";
            
            case 'Final_Project':
                return "CREATE TABLE Final_Project (
                    ProjectID VARCHAR(50) PRIMARY KEY,
                    ProjectName VARCHAR(100) NOT NULL,
                    CourseID VARCHAR(50) NOT NULL,
                    StudentID VARCHAR(50) NOT NULL,
                    FOREIGN KEY (CourseID) REFERENCES Course(CourseID),
                    FOREIGN KEY (StudentID) REFERENCES Student(StudentID)
                )";
            
            case 'Course_Student':
                return "CREATE TABLE Course_Student (
                    StudentID VARCHAR(50) NOT NULL,
                    CourseID VARCHAR(50) NOT NULL,
                    Grade VARCHAR(10) NOT NULL,
                    PRIMARY KEY (StudentID, CourseID),
                    FOREIGN KEY (StudentID) REFERENCES Student(StudentID),
                    FOREIGN KEY (CourseID) REFERENCES Course(CourseID)
                )";
            
            default:
                return "";
        }
    }
?>