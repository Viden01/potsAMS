<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture and Submit Photo</title>
</head>
<body>
    <h3>Employee Attendance</h3>
    <video id="video" width="400" height="300" autoplay></video>
    <canvas id="canvas" width="400" height="300" style="display: none;"></canvas>
    <button id="capture">Capture Photo</button>
    <button id="submitPhoto" style="display: none;">Submit Photo</button>

    <form id="attendanceForm" action="submit_attendance.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="photo" id="photo">
        <input type="text" name="employee_id" placeholder="Employee ID" required>
        <button type="submit">Submit Attendance</button>
    </form>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const submitButton = document.getElementById('submitPhoto');
        const photoInput = document.getElementById('photo');

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
            const context = canvas.getContext('2d');
            canvas.style.display = 'block';
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            video.style.display = 'none';
            submitButton.style.display = 'inline';
            
            // Convert the image to a base64 string
            const imageData = canvas.toDataURL('image/png');
            photoInput.value = imageData;
        });

        // Optionally show video again for retake
        submitButton.addEventListener('click', () => {
            video.style.display = 'block';
            canvas.style.display = 'none';
        });
    </script>
</body>
</html>
