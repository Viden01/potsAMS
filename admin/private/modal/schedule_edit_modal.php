<!-- Add -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Edit Schedule</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST">
               <input  type="hidden"  id="id"  class="form-control"/>
                <div id="msg"></div>
          		  <div class="form-group">
                  	<label for="time_in" class="col-sm-3 control-label">Time In</label>

                  	<div class="col-sm-9">
                      <div >
                       <input id="edit_timein"  alt="time_in" name="time_in" class="form-control timepicker1" type="text" name="timepicker1" required="" />
                      </div>
                  	</div>
                </div>
                <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label">Time Out</label>

                    <div class="col-sm-9">
                      <div>
                        <input id="edit_timeout" alt="time_out" name="time_out" class="form-control timepicker1" type="text" name="timepicker1" required="" />
                      </div>
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="button" class="btn btn-primary btn-flat" name="add" id="editsched"><i class="fa fa-save"></i> Update</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
     <script type="text/javascript">
             /*=====================start sched=====================*/  
       $(document).ready(function() {
           $('#editsched').click(function(e) {
              e.preventDefault();///i hahandle nito yung submit button kung wala laman na, nilagay sa field
         
                const time_in = $('input[id=edit_timein]').val();
                const time_out = $('input[id=edit_timeout]').val();
                const id = $('input[id=id]').val();

                 $.ajax({
         
                     type: 'POST',
                     data: {
                            time_in: time_in,
                            time_out: time_out,
                            id: id
                       },
                     url: 'processing/Edit_schedule_process.php',
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
