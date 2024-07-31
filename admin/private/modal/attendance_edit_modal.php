<!-- Add -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Edit Attendance</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST">
                <input id="id" class="form-control" type="hidden" />
                <div id="msg"></div>
                <div class="form-group">
                    <label for="employee_name" class="col-sm-3 control-label">Employee Name</label>
                    <div class="col-sm-9">
                        <div>
                            <input id="employee_name" class="form-control" type="text" required="" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="employee_id" class="col-sm-3 control-label">Employee ID</label>
                    <div class="col-sm-9">
                        <div>
                            <input id="employee_id" class="form-control" type="text" readonly="" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_in" class="col-sm-3 control-label">Time In</label>
                    <div class="col-sm-9">
                        <div>
                            <input id="edit_timein" alt="time_in" name="time_in" class="form-control timepicker1" type="text" required="" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label">Time Out</label>
                    <div class="col-sm-9">
                        <div>
                            <input id="edit_timeout" alt="time_out" name="time_out" class="form-control timepicker2" type="text" required="" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_attendance" class="col-sm-3 control-label">Date</label>
                    <div class="col-sm-9">
                        <div>
                            <input id="edit_date" alt="date_attendance" name="date_attendance" class="form-control" type="date" required="" />
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
    /*=====================start attendance=====================*/  
    $(document).ready(function() {
        $('#editsched').click(function(e) {
            e.preventDefault();
            
            const id = $('input[id=id]').val();
            const employee_name = $('input[id=employee_name]').val();
            const time_in = $('input[id=edit_timein]').val();
            const time_out = $('input[id=edit_timeout]').val();
            const date_attendance = $('input[id=edit_date]').val();

            $.ajax({
                type: 'POST',
                data: {
                    id: id,
                    employee_name: employee_name,
                    time_in: time_in,
                    time_out: time_out,
                    date_attendance: date_attendance
                },
                url: 'processing/Edit_attendance_process.php',
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
