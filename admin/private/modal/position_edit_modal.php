<!-- Add -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Edit Position</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST">
                   <input id="id" class="form-control" type="hidden" />
                <div id="msg"></div>
               <div class="form-group">
                  <label for="time_in" class="col-sm-3 control-label">Emp Position</label>

                  <div class="col-sm-9">
                    <div >
                     <input id="edit_empposition" alt="emp_position" name="emp_position" class="form-control" type="text" required="" />
                    </div>
                  </div>
              </div>

                 <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label"> Rate per hour</label>

                    <div class="col-sm-9">
                      <div>
                        <input id="edit_rateperhour" alt="rate_per_hour" name="rate_per_hour" class="form-control" type="text"  required="" />
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="button" class="btn btn-primary btn-flat" name="add" id="editposition"><i class="fa fa-save"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>
     <script type="text/javascript">
      /*=====================start position=====================*/  
       $(document).ready(function() {
           $('#editposition').click(function(e) {
              e.preventDefault();///i hahandle nito yung submit button kung wala laman na, nilagay sa field
         
                const emp_position = $('input[id=edit_empposition]').val();
                const rate_per_hour = $('input[id=edit_rateperhour]').val();
                const id = $('input[id=id]').val();

                 $.ajax({
         
                     type: 'POST',
                     data: {
                            emp_position: emp_position,
                            rate_per_hour: rate_per_hour,
                            id: id
                       },
                     url: 'processing/Edit_position_process.php',
                     async: false,
                     cache: false,
                     success: function(data) {
                      $("#msg").html(data);
                      window.location.reload(true);
                     }
         
                 });
         
             }); 
         });

      /*=====================end  position=====================*/  
     </script>
