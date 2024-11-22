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
            <form class="text-center border border-light p-5" id="punch">
               <p class="h4 mb-3">ATTENDANCE TRACKER</p>
               <input type="text" id="employee_id" name="employee_id" class="form-control mb-4" placeholder="Employee ID" autocomplete="off" required="">
               <select class="browser-default custom-select mb-4" name="status" autofocus="off" required="">
                  <option value="in" selected>Time In</option>
                  <option value="out">Time Out</option>
               </select>
               <button type="submit" class="btn btn-info btn-block">Punch</button>
            </form>
            <!-- Form -->
         </div>
      </div>
      <?php include('footer/footer.php'); ?>
</body>
</html>
