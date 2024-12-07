<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture and Submit Photo</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            /* Test with a simple solid background color first */
            background-color: #f4f7fa; /* Light gray background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background for better readability */
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
            box-sizing: border-box;
        }

        h3 {
            color: #333;
            font-size: 26px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        video {
            border: 2px solid #ddd;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
            display: block;
            margin: 0 auto;
        }

        canvas {
            display: none;
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }

        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #218838;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .form-group {
            margin-top: 20px;
        }

        .form-group input {
            padding: 12px;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        }

        .form-group button {
            width: 100%;
            max-width: 300px;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .action-buttons button {
            width: 48%;
        }

        #submitPhoto {
            display: none;
        }
    </style>
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
                <input type="text" name="employee_id" placeholder="Employee ID" required>
            </div>
            <input type="hidden" name="photo" id="photo">
            <div class="form-group">
                <button type="submit" disabled id="submitAttendance">Submit Attendance</button>
            </div>
        </form>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const submitButton = document.getElementById('submitPhoto');
        const photoInput = document.getElementById('photo');
        const submitAttendanceButton = document.getElementById('submitAttendance');

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
    </script>
</body>

</html>
