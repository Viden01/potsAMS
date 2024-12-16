<?php
include '../../connection/db_conn.php';

// Retrieve input data from the form
$first_name = ucfirst(trim($_POST['first_name']));
$middle_name = ucfirst(trim($_POST['middle_name']));
$last_name = ucfirst(trim($_POST['last_name']));
$barangay = ucfirst(trim($_POST['barangay']));
$municipality = ucfirst(trim($_POST['municipality']));
$city = ucfirst(trim($_POST['city']));
$birth_date = $_POST['birth_date'];
$Mobile_number = $_POST['Mobile_number'];
$gender = $_POST['gender'];
$position_id = $_POST['position_id'];
$marital_status = $_POST['marital_status'];
$schedule_id = $_POST['schedule_id'];
$fileName = '';

// Combine address into one string
$complete_address = $barangay . ', ' . $municipality . ', ' . $city;

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['size'] > 0) {
    $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
    $originalFileName = $_FILES['profile_pic']['name'];
    $fileSize = $_FILES['profile_pic']['size'];
    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg'];
    
    // Validate file type
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo '<div class="alert alert-warning">Only JPG/JPEG files are allowed!</div>';
        exit();
    }
    
    // Validate file size (e.g., max 5MB)
    if ($fileSize > 5 * 1024 * 1024) {
        echo '<div class="alert alert-warning">File size must not exceed 5MB!</div>';
        exit();
    }
    
    // Save the file with a unique name
    $fileName = "../../../images/" . time() . '_' . uniqid() . '.' . $fileExtension;
    if (!move_uploaded_file($fileTmpPath, $fileName)) {
        echo '<div class="alert alert-danger">Error uploading file. Please try again.</div>';
        exit();
    }
}

// Check if the employee name already exists
$sql = "SELECT * FROM employee_records WHERE first_name = ? AND last_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $first_name, $last_name);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo '<div class="alert alert-warning">Name already exists!</div>';
    exit();
}

// Generate unique employee ID using the new format
function generateEmployeeID($conn, $prefix = "AWG") {
    $sql = "SELECT emp_id FROM employee_records WHERE emp_id LIKE '$prefix%' ORDER BY emp_id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['emp_id'];
        $numeric_part = (int)substr($last_id, strlen($prefix));
        $new_numeric_part = str_pad($numeric_part + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $new_numeric_part = "001";
    }

    return $prefix . $new_numeric_part;
}

$employee_id = generateEmployeeID($conn);

// Insert the new employee record into the database
$sql = "INSERT INTO employee_records (emp_id, first_name, middle_name, last_name, complete_address, birth_date, Mobile_number, gender, position_id, marital_status, schedule_id, profile_pic, date_created)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssssssss', $employee_id, $first_name, $middle_name, $last_name, $complete_address, $birth_date, $Mobile_number, $gender, $position_id, $marital_status, $schedule_id, $fileName);

if ($stmt->execute()) {
    echo '<div class="alert alert-success">Employee added successfully!</div>';
} else {
    echo '<div class="alert alert-danger">Failed to add employee.</div>';
}
?>
