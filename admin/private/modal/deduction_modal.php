<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add Deduction</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST">
                <div id="msg"></div>
               <div class="form-group">
                  <label for="time_in" class="col-sm-3 control-label">Deduction Name</label>

                  <div class="col-sm-9">
                    <div >
                     <input id="deduction_name" alt="deduction_name" name="deduction_name" class="form-control" type="text" required="" />
                    </div>
                  </div>
              </div>

                 <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label"> Amount Deduction</label>

                    <div class="col-sm-9">
                      <div>
                        <input id="amount" alt="amount" name="amount" class="form-control" type="text"  required="" />
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="button" class="btn btn-primary btn-flat" name="add" id="adddeduc"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
    </div>
</div>
     <script type="text/javascript">
      /*=====================start deduc=====================*/  
       $(document).ready(function() {
           $('#adddeduc').click(function(e) {
              e.preventDefault();///i hahandle nito yung submit button kung wala laman na, nilagay sa field
         
                const deduction_name = $('input[alt=deduction_name]').val();
                const amount = $('input[alt=amount]').val();

                 $.ajax({
         
                     type: 'POST',
                     data: {
                            deduction_name: deduction_name,
                            amount: amount
                       },
                     url: 'processing/deduction_process.php',
                     async: false,
                     cache: false,
                     success: function(data) {
                      $("#msg").html(data);
                      window.location.reload(true);
                     }
         
                 });
         
             }); 
         });

      /*=====================end  deduc=====================*/  
     </script>
