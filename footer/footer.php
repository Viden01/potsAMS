            <!-- Footer -->
         <footer class="page-footer font-small cyan darken-3 mt-2">
            <!-- Footer Elements -->
            <div class="container">
               <!-- Grid row-->
               <div class="row">
                  <!-- Grid column -->
                  <div class="col-md-12 py-5">
                     <div class="mb-5 flex-center">
                        <!-- Facebook -->
                        <a class="fb-ic">
                        <i class="fab fa-facebook-f fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
                        </a>
                        <!-- Twitter -->
                        <a class="tw-ic">
                        <i class="fab fa-twitter fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
                        </a>
                        <!-- Google +-->
                        <a class="gplus-ic">
                        <i class="fab fa-google-plus-g fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
                        </a>
                        <!--Linkedin -->
                        <a class="li-ic">
                        <i class="fab fa-linkedin-in fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
                        </a>
                        <!--Instagram-->
                        <a class="ins-ic">
                        <i class="fab fa-instagram fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
                        </a>
                        <!--Pinterest-->
                        <a class="pin-ic">
                        <i class="fab fa-pinterest fa-lg white-text fa-2x"> </i>
                        </a>
                     </div>
                  </div>
                  <!-- Grid column -->
               </div>
               <!-- Grid row-->
            </div>
            <!-- Footer Elements -->
            <!-- Copyright -->
            <div class="footer-copyright text-center py-3">© 2020 Copyright:
               <a href="#"> Created By: Juniltoledo</a>
            </div>
            <!-- Copyright -->
         </footer>
         <!-- Footer -->
      </div>
      <?php include 'jsconnect.php' ?>
      <!-- jQuery -->
      <script type="text/javascript">  
          $(function() {
            var interval = setInterval(function() {
              var momentNow = moment();
              $('#date').html(momentNow.format('dddd').substring(0,9).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));  
              $('#time').html(momentNow.format('hh:mm:ss A'));
            }, 100);
          
            $('#punch').submit(function(e){
              e.preventDefault();
              var trackingtime = $(this).serialize();
              $.ajax({
                type: 'POST',
                url: 'public/employee_attendance.php',
                data: trackingtime,
                dataType: 'json',
                success: function(response){
                  if(response.error){
                    $('.alert').hide();
                    $('.alert-danger').show();
                    $('.message').html(response.message);
                  }
                  else{
                    $('.alert').hide();
                    $('.alert-success').show();
                    $('.message').html(response.message);
                    $('#employee_id').val('');
                  }
                }
              });
            });
              
          });
      </script>
      <!-- Bootstrap tooltips -->
      <script type="text/javascript" src="js/popper.min.js"></script>
      <!-- Bootstrap core JavaScript -->
      <script type="text/javascript" src="js/bootstrap.min.js"></script>
      <!-- MDB core JavaScript -->
      <script type="text/javascript" src="js/mdb.min.js"></script>
      <!-- Your custom scripts (optional) -->