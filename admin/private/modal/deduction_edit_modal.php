<!-- Add -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Edit Deduction</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST">
                    <input type="hidden" id="id" class="form-control"  />
                <div id="msg"></div>
               <div class="form-group">
                  <label for="time_in" class="col-sm-3 control-label">Deduction Name</label>

                  <div class="col-sm-9">
                    <div >
                     <input id="edit_deductionname" alt="deduction_name" name="deduction_name" class="form-control" type="text" required="" />
                    </div>
                  </div>
              </div>

                 <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label"> Amount Deduction</label>

                    <div class="col-sm-9">
                      <div>
                        <input id="edit_amount" alt="amount" name="amount" class="form-control" type="text"  required="" />
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
         
                const deduction_name = $('input[id=edit_deductionname]').val();
                const amount = $('input[id=edit_amount]').val();
                const id = $('input[id=id]').val();

                 $.ajax({
         
                     type: 'POST',
                     data: {
                            deduction_name: deduction_name,
                            amount: amount,
                            id: id
                       },
                     url: 'processing/Edit_deduction_process.php',
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
