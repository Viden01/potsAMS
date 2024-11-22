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
      <footer class="page-footer mt-2" style="margin-bottom: 4px;background-color: #17a2b8;font-size: 150%;">
         <div class="footer-copyright text-center py-2">
            <p id="date"></p>
            <font color="#ff9999">
               <p id="time" style="font-weight: bold;font-size: 250%;font-family:'digital-clock-font';"></p>
            </font>
         </div>
      </footer>
      
      <div class="row">
         <div class="col-sm">
            <!-- Success & Error Messages -->
            <div class="card" style="border:1px solid #d4edda;background-color: #d4edda!important;">
               <div id="alert-message-success" class="alert alert-success alert-dismissible mt20 text-center" style="display:none;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <span class="result"><i class="fas fa-tachometer-alt"></i> <span class="message"></span></span>
               </div>
               <div id="alert-message-danger" class="alert alert-danger alert-dismissible mt20 text-center" style="display:none;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
               </div>
            </div>
         </div>
         <div class="col-sm">
            <!-- Camera Section -->
            <div class="text-center">
               <video id="video" width="100%" autoplay></video>
               <button id="capture" class="btn btn-info btn-block mt-2">Take Selfie</button>
               <canvas id="canvas" style="display:none;"></canvas>
            </div>

            <!-- Attendance Form -->
            <form class="text-center border border-light p-5" id="punch">
               <p class="h4 mb-3">ATTENDANCE TRACKER</p>
               <input type="text" id="employee_id" name="employee_id" class="form-control mb-4" placeholder="Employee ID" autocomplete="off" required="">
               <select class="browser-default custom-select mb-4" name="status" required="">
                  <option value="in" selected>Time In</option>
                  <option value="out">Time Out</option>
               </select>
               <input type="hidden" id="imageData" name="imageData">
               <input type="hidden" id="latitude" name="latitude">
               <input type="hidden" id="longitude" name="longitude">
               <button type="submit" class="btn btn-info btn-block" id="submit-btn" disabled>Submit Attendance</button>
            </form>
         </div>
      </div>
   </div>
   <?php include('footer/footer.php'); ?>

   <script>
      // Access the webcam
      const video = document.getElementById('video');
      const canvas = document.getElementById('canvas');
      const captureBtn = document.getElementById('capture');
      const submitBtn = document.getElementById('submit-btn');
      const imageDataInput = document.getElementById('imageData');
      const latitudeInput = document.getElementById('latitude');
      const longitudeInput = document.getElementById('longitude');

      // Enable webcam
      navigator.mediaDevices.getUserMedia({ video: true })
         .then(stream => { video.srcObject = stream; })
         .catch(error => { alert('Camera access denied or unavailable.'); });

      // Capture image and get geolocation
      captureBtn.addEventListener('click', () => {
         // Draw image from video to canvas
         canvas.width = video.videoWidth;
         canvas.height = video.videoHeight;
         canvas.getContext('2d').drawImage(video, 0, 0);
         const imageData = canvas.toDataURL('image/png');
         imageDataInput.value = imageData;

         // Get geolocation
         if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
               latitudeInput.value = position.coords.latitude;
               longitudeInput.value = position.coords.longitude;
               submitBtn.disabled = false; // Enable submit button after selfie and location
            }, () => { alert('Geolocation access denied.'); });
         } else {
            alert('Geolocation is not supported by this browser.');
         }
      });
   </script>
</body>
</html>
