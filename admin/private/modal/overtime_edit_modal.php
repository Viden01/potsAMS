<!-- Add -->
<div class="modal fade" id="edit">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Edit Overtime</b></h4>
          </div>
         <form method="POST">
            <div class="modal-body">
              <input type="hidden" class="form-control" id="id" name="edit_id" autocomplete="off">
               <div id="msg"></div>
               <div class="form-row">
                  <div class="form-group col-md-12">
                     <label for="CompleteAddress">Employee ID</label>
                      <input type="text" class="form-control" id="edit_employeeid" name="employee_id" autocomplete="off">
                  </div>
               </div>
                 <div class="form-row">
                   <div class="form-group col-md-6">
                     <label for="MobileNumber">No. of Hours</label>
                     <input type="text" class="form-control" id="edit_hours" name="overtime_hours" autocomplete="off">
                  </div>
                 <div class="form-group col-md-6">
                     <label for="Birthday">No. of Mins</label>
                     <input type="text" class="form-control" id="edit_mins" name="overtime_mins" autocomplete="off">
                  </div>
               </div>
                <div class="form-row">
                <div class="form-group col-md-6">
                     <label for="MobileNumber">Rate</label>
                     <input type="text" class="form-control" id="edit_rate" name="overtime_rate" autocomplete="off">
                  </div>
                    <div class="form-group col-md-6">
                     <label for="Birthday">Date</label>
                     <input type="date" class="form-control" id="edit_date" name="overtime_date" autocomplete="off">
                  </div>
               </div>
               
               </div>
      
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" id="editover">Edit</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!--end Add Modal -->
<script type="text/javascript">
   $(document).ready(function() {
   
    document.getElementById("editover").addEventListener("click", (e) =>{
       e.preventDefault();
     
      const id = document.querySelector('input[id=id]').value;
      const employee_id = document.querySelector('input[id=edit_employeeid]').value;
      const overtime_hours = document.querySelector('input[id=edit_hours]').value;
      const overtime_mins = document.querySelector('input[id=edit_mins]').value;
      const overtime_rate = document.querySelector('input[id=edit_rate]').value;
      const overtime_date = document.querySelector('input[id=edit_date]').value;

         var delay = 100;
               var d = new FormData(this.form);
               d.append('id', id);
               d.append('employee_id', employee_id);
               d.append('overtime_hours', overtime_hours);
               d.append('overtime_mins', overtime_mins);
               d.append('overtime_rate', overtime_rate);
               d.append('overtime_date', overtime_date);
   
            $.ajax({
                url: 'processing/Edit_overtime_process.php',
                type: "POST",
                data: d,
                processData: false,
                contentType: false,
   
                async: false,
                cache: false,
   
                success: function(data) {
                    setTimeout(function() {
                        $('#msg').html(data);
                    }, delay);
                   setTimeout(location.reload.bind(location), 200);
   
                },
                error: function(data) {
                    console.log("Failed");
                }
            });
   
        });
    });
</script>

