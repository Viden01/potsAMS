<?php
session_start();
// Security headers
include "header/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture and Submit Photo</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h3>Attendance Tracker</h3>

        <div>
            <video id="video" autoplay></video>
            <canvas id="canvas"></canvas>
        </div>

        <div class="action-buttons">
            <button id="capture">Capture Photo</button>
            <button id="submitPhoto">Submit Photo</button>
        </div>

        <form id="attendanceForm" action="submit_attendance.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="employee_id" placeholder="Employee ID" id="employee_id" required>
            </div>
            <input type="hidden" name="photo" id="photo">
            <input type="hidden" name="time_in" id="time_in">
            <input type="hidden" name="time_out" id="time_out">
            <!-- Hidden inputs for latitude and longitude -->
            <input type="text" name="latitude" id="latitude">
            <input type="text" name="longitude" id="longitude">

            <!-- Dropdown for Attendance Type -->
            <div class="form-group">
                <label for="attendance_type">Select Attendance Type: </label>
                <select name="attendance_type" id="attendance_type">
                    <option value="time_in">Time-In</option>
                    <option value="time_out">Time-Out</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" disabled id="submitAttendance">Submit Attendance</button>
            </div>
        </form>

    </div>

    <?php include "footer/sweetalert.php";?>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const submitButton = document.getElementById('submitPhoto');
        const photoInput = document.getElementById('photo');
        const submitAttendanceButton = document.getElementById('submitAttendance');
        const timeInInput = document.getElementById('time_in');
        const timeOutInput = document.getElementById('time_out');
        const attendanceTypeSelect = document.getElementById('attendance_type');
        const employeeIdInput = document.getElementById('employee_id');
        const latitudeInput = document.getElementById('latitude');  // Latitude input
        const longitudeInput = document.getElementById('longitude');  // Longitude input

        // Access the user's camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing camera: ", err);
            });

        // Capture and freeze the photo
        captureButton.addEventListener('click', () => {
            if (!employeeIdInput.value.trim()) {
                alert('Please enter your Employee ID first.');
                return; // Prevent capturing the photo if Employee ID is not entered
            }

            const context = canvas.getContext('2d');
            canvas.style.display = 'block';
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            video.style.display = 'none';
            submitButton.style.display = 'inline';

            // Convert the image to a base64 string
            const imageData = canvas.toDataURL('image/png');
            photoInput.value = imageData;

            // Record time-in when the photo is captured
            const currentTime = new Date().toISOString();
            timeInInput.value = currentTime;

            // Enable submit attendance button
            submitAttendanceButton.disabled = false;
        });

        // Show video again for retake
        submitButton.addEventListener('click', () => {
            video.style.display = 'block';
            canvas.style.display = 'none';
            submitButton.style.display = 'none';
            submitAttendanceButton.disabled = true; // Disable the submit button until a new photo is taken
        });

        // Get user's location and store latitude and longitude in hidden inputs
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    latitudeInput.value = position.coords.latitude;  // Store latitude in hidden input
                    longitudeInput.value = position.coords.longitude;  // Store longitude in hidden input
                }, function() {
                    alert("Unable to retrieve your location.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Call the function to get location when the page loads
        getLocation();

        // Form submission validation
        document.getElementById('attendanceForm').addEventListener('submit', (event) => {
            // Check if Employee ID is provided
            if (!employeeIdInput.value.trim()) {
                // Display SweetAlert2 alert
                Swal.fire({
                    title: 'Error!',
                    text: 'Please enter your Employee ID.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                event.preventDefault();  // Prevent form submission
                return;
            }

            // Check if the photo is captured
            if (!photoInput.value.trim()) {
                // Display SweetAlert2 alert
                Swal.fire({
                    title: 'Error!',
                    text: 'Please capture a photo before submitting.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                event.preventDefault();  // Prevent form submission
                return;
            }

            // Check if the attendance type is selected
            const selectedAttendanceType = attendanceTypeSelect.value;

            if (selectedAttendanceType === 'time_out') {
                // If Time-Out is selected, record time-out
                timeOutInput.value = new Date().toISOString();
            } else {
                // If Time-In is selected, ensure time-out is not sent
                timeOutInput.value = ''; // Clear time-out if time-in is selected
            }
        });
    </script>
</body>
</html>
