<?php
include '../../connection/db_conn.php';

// Function to parse the complete address into Barangay, Municipality, and City
function parseAddress($address) {
    $parts = array_map('trim', explode(',', $address));
    return [
        'barangay' => $parts[0] ?? '',
        'municipality' => $parts[1] ?? '',
        'city' => $parts[2] ?? ''
    ];
}

// Collecting form data
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

// Handle profile picture upload
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['size'] > 0) {
    $fileName = "../../../images/" . time() . '_' . basename($_FILES["profile_pic"]["name"]);
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $fileName);
}

// Check if the employee already exists
$sql = "SELECT * FROM employee_records WHERE first_name = ? AND last_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $first_name, $last_name);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo '<div class="alert alert-warning">Name already exists!</div>';
    exit();
}

// Generate unique employee ID
$employee_id = strtoupper(substr(md5(uniqid()), 0, 6));

// Insert new employee record
$sql = "INSERT INTO employee_records (employee_id, first_name, middle_name, last_name, barangay, municipality, city, birth_date, Mobile_number, gender, position_id, marital_status, schedule_id, profile_pic, date_created)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'sssssssssssss',
    $employee_id,
    $first_name,
    $middle_name,
    $last_name,
    $barangay,
    $municipality,
    $city,
    $birth_date,
    $Mobile_number,
    $gender,
    $position_id,
    $marital_status,
    $schedule_id,
    $fileName
);

if ($stmt->execute()) {
    echo '<div class="alert alert-success">Employee added successfully!</div>';
} else {
    echo '<div class="alert alert-danger">Failed to add employee.</div>';
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
