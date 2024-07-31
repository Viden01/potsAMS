<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add Position</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST">
                <div id="msg"></div>
               <div class="form-group">
                  <label for="time_in" class="col-sm-3 control-label">Emp Position</label>

                  <div class="col-sm-9">
                    <div >
                     <input id="emp_position" alt="emp_position" name="emp_position" class="form-control" type="text" required="" />
                    </div>
                  </div>
              </div>

                 <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label"> Rate per hour</label>

                    <div class="col-sm-9">
                      <div>
                        <input id="rate_per_hour" alt="rate_per_hour" name="rate_per_hour" class="form-control" type="text"  required="" />
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="button" class="btn btn-primary btn-flat" name="add" id="addposition"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
    </div>
</div>
     <script type="text/javascript">
      /*=====================start attendance=====================*/  
       $(document).ready(function() {
           $('#addposition').click(function(e) {
              e.preventDefault();///i hahandle nito yung submit button kung wala laman na, nilagay sa field
         
                const emp_position = $('input[alt=emp_position]').val();
                const rate_per_hour = $('input[alt=rate_per_hour]').val();

                 $.ajax({
         
                     type: 'POST',
                     data: {
                            emp_position: emp_position,
                            rate_per_hour: rate_per_hour
                       },
                     url: 'processing/position_process.php',
                     async: false,
                     cache: false,
                     success: function(data) {
                      $("#msg").html(data);
                      window.location.reload(true);
                     }
         
                 });
         
             }); 
         });

      /*=====================end  attendance=====================*/  
     </script>
