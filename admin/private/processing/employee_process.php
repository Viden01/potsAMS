<?php
error_reporting(0);
include '../../connection/db_conn.php';

$first_name = $conn->real_escape_string(strip_tags(ucfirst($_POST['first_name'])));
$middle_name = $conn->real_escape_string(strip_tags(ucfirst($_POST['middle_name'])));
$last_name = $conn->real_escape_string(strip_tags(ucfirst($_POST['last_name'])));
$complete_address = $conn->real_escape_string(strip_tags($_POST['complete_address']));
$birth_date = $conn->real_escape_string(strip_tags($_POST['birth_date']));
$Mobile_number = $conn->real_escape_string(strip_tags($_POST['Mobile_number']));
$gender = $conn->real_escape_string(strip_tags($_POST['gender']));
$position_id = $conn->real_escape_string(strip_tags($_POST['position_id']));
$marital_status = $conn->real_escape_string(strip_tags($_POST['marital_status']));
$schedule_id = $conn->real_escape_string(strip_tags($_POST['schedule_id']));

$image = addslashes(file_get_contents($_FILES['profile_pic']['tmp_name']));
$image_name = addslashes($_FILES['profile_pic']['name']);
$image_size = getimagesize($_FILES['profile_pic']['tmp_name']);
move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../../../images/" . date("Ymd") . time() . '_' . $_FILES["profile_pic"]["name"]);
$fileName = "../../../images/" . date("Ymd") . time() . '_' . $_FILES["profile_pic"]["name"];

$letters = '';
$numbers = '';
foreach (range('A', 'Z') as $char) {
    $letters .= $char;
}
for ($i = 0; $i < 3; $i++) {
    $numbers .= $i;
}
$employee_id = substr(str_shuffle($letters), 0, 3) . substr(str_shuffle($numbers), 0, 9);

// Check if the name already exists
$sql = "SELECT * FROM `employee_records` WHERE first_name = ? AND last_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $first_name, $last_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo 'warning: Name already exists!';
    $stmt->close();
    $conn->close();
    exit();
}

// Proceed with inserting the new employee record
$sql = "INSERT INTO `employee_records` (employee_id, first_name, middle_name, last_name, complete_address, birth_date, Mobile_number, gender, position_id, marital_status, schedule_id, profile_pic, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssss", $employee_id, $first_name, $middle_name, $last_name, $complete_address, $birth_date, $Mobile_number, $gender, $position_id, $marital_status, $schedule_id, $fileName);

if ($stmt->execute()) {
    echo '<div class="alert alert-success">
        <strong><i class="fas fa-check"></i>&nbsp;&nbsp;Insert Successfully!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
} else {
    echo '<div class="alert alert-warning">
        <strong><i class="fas fa-times"></i>&nbsp;Insert Failed!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
}

$stmt->close();
$conn->close();
?>
