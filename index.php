<?php include('header/head.php'); ?>
<body>
   <!-- Disable right-click -->
   <script>
      document.addEventListener('contextmenu', function(e) {
         e.preventDefault();
      });
   </script>

   <div class="container mb-2">
      <!-- Footer -->
      <footer class="page-footer mt-2" style="margin-bottom: 4px;background-color: #17a2b8;;font-size: 150%;">
         <div class="footer-copyright text-center py-2">
            <p id="date"></p>
            <font color="#ff9999">
               <p id="time" style="font-weight: bold;font-size: 250%;font-family:'digital-clock-font';"></p>
            </font>
         </div>
      </footer>
      <!-- Footer -->
      <div class="row">
         <div class="col-sm">
            <!-- Card -->
            <div class="card" style="border:1px solid #d4edda;height: 100%;width: 100%;background-color: #d4edda!important;">
               <div id="alert-message-success" class="alert alert-success alert-dismissible mt20 text-center" style="display:none;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <span class="result"><i class="fas fa-tachometer-alt"></i> <span class="message"></span></span>
               </div>
               <div id="alert-message-danger" class="alert alert-danger alert-dismissible mt20 text-center" style="display:none;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
               </div>
            </div>
            <!-- Card -->
         </div>
         <div class="col-sm">
            <!-- Form -->
            <form class="text-center border border-light p-5" id="punch" method="POST" enctype="multipart/form-data">
               <p class="h4 mb-3">ATTENDANCE TRACKER</p>
               
               <!-- Employee ID -->
               <input type="text" id="employee_id" name="employee_id" class="form-control mb-4" placeholder="Employee ID" autocomplete="off" required="">

               <!-- Status: Time In or Time Out -->
               <select class="browser-default custom-select mb-4" name="status" autofocus="off" required="">
                  <option value="in" selected>Time In</option>
                  <option value="out">Time Out</option>
               </select>

               <!-- Geocam: Take Selfie and Capture Location -->
               <div class="mb-4">
                  <video id="video" width="300" height="200" autoplay></video><br>
                  <button type="button" id="capture" class="btn btn-primary btn-block">Capture Selfie</button>
                  <canvas id="canvas" style="display:none;"></canvas>
               </div>
               
               <!-- Hidden Inputs for Selfie and Location -->
               <input type="hidden" id="location" name="location" value="">
               <input type="hidden" id="selfie" name="selfie" value="">

               <button type="submit" class="btn btn-info btn-block" id="submitButton" disabled>Punch</button>
            </form>
            <!-- Form -->
         </div>
      </div>
   </div>

   <script>
      // Get video stream from camera
      const video = document.getElementById('video');
      const canvas = document.getElementById('canvas');
      const captureButton = document.getElementById('capture');
      const submitButton = document.getElementById('submitButton');
      const locationInput = document.getElementById('location');
      const selfieInput = document.getElementById('selfie');

      // Get user media (video) for webcam
      navigator.mediaDevices.getUserMedia({ video: true })
         .then((stream) => {
            video.srcObject = stream;
         })
         .catch((err) => {
            console.log("Error accessing the camera: " + err);
         });

      // Capture selfie
      captureButton.addEventListener('click', () => {
         // Draw current frame on canvas
         const context = canvas.getContext('2d');
         context.drawImage(video, 0, 0, canvas.width, canvas.height);

         // Convert canvas image to base64 string
         const selfieData = canvas.toDataURL('image/jpeg');
         selfieInput.value = selfieData;

         // Get geolocation
         if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
               const latitude = position.coords.latitude;
               const longitude = position.coords.longitude;
               
               locationInput.value = latitude + ',' + longitude;

               // Enable the submit button after selfie and location are captured
               submitButton.disabled = false;
            });
         } else {
            alert("Geolocation is not supported by this browser.");
         }
      });
   </script>
</body>
</html>
