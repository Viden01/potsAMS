<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add Schedule</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST">
                <div id="msg"></div>
          		  <div class="form-group">
                  	<label for="time_in" class="col-sm-3 control-label">Time In</label>

                  	<div class="col-sm-9">
                      <div >
                       <input id="timepicker1" alt="time_in" name="time_in" class="form-control" type="text" name="timepicker1" required="" />
                      </div>
                  	</div>
                </div>
                <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label">Time Out</label>

                    <div class="col-sm-9">
                      <div>
                        <input id="timepicker2" alt="time_out" name="time_out" class="form-control" type="text" name="timepicker1" required="" />
                      </div>
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="button" class="btn btn-primary btn-flat" name="add" id="addsched"><i class="fa fa-save"></i> Save</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
     <script type="text/javascript">
             /*=====================start sched=====================*/  
       $(document).ready(function() {
           $('#addsched').click(function(e) {
              e.preventDefault();///i hahandle nito yung submit button kung wala laman na, nilagay sa field
         
                const time_in = $('input[alt=time_in]').val();
                const time_out = $('input[alt=time_out]').val();

                 $.ajax({
         
                     type: 'POST',
                     data: {
                            time_in: time_in,
                            time_out: time_out
                       },
                     url: 'processing/schedule_process.php',
                     async: false,
                     cache: false,
                     success: function(data) {
                      $("#msg").html(data);
                      window.location.reload(true);
                     }
         
                 });
         
             }); 
         });

      /*=====================end  sched=====================*/  
     </script>
