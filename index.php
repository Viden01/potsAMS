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
            <input type="text" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

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
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing camera: ", err);
            });

        captureButton.addEventListener('click', () => {
            if (!employeeIdInput.value.trim()) {
                alert('Please enter your Employee ID first.');
                return;
            }

            const context = canvas.getContext('2d');
            canvas.style.display = 'block';
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            video.style.display = 'none';
            submitButton.style.display = 'inline';

            const imageData = canvas.toDataURL('image/png');
            photoInput.value = imageData;

            const currentTime = new Date().toISOString();
            timeInInput.value = currentTime;

            submitAttendanceButton.disabled = false;

            getLocation();
        });

        submitButton.addEventListener('click', () => {
            video.style.display = 'block';
            canvas.style.display = 'none';
            submitButton.style.display = 'none';
            submitAttendanceButton.disabled = true;
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    latitudeInput.value = latitude;
                    longitudeInput.value = longitude;
                }, function() {
                    alert("Unable to retrieve your location.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        document.getElementById('attendanceForm').addEventListener('submit', (event) => {
            if (!employeeIdInput.value.trim()) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please enter your Employee ID.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                event.preventDefault();
                return;
            }

            if (!photoInput.value.trim()) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please capture a photo before submitting.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                event.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
