<!-- Add -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"><b><span class="date">Employee Name:</span> <span id="edit_employeename"></span></b></h4>
          	</div>
          	<div class="modal-body">
             <div id="msg"></div>
            	<form class="form-horizontal" method="POST">
                <input type="hidden" id="id" name="id">
                <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label">Amount</label>
                    <div class="col-sm-9">
                      <div>
                        <input id="edit_amount" alt="amount"  class="form-control" type="text" required="" />
                      </div>
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="button" class="btn btn-primary btn-flat" name="add" id="editscash"><i class="fa fa-save"></i> Update</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
     <script type="text/javascript">
             /*=====================start cashadv=====================*/  
       $(document).ready(function() {
           $('#editscash').click(function(e) {
              e.preventDefault();///i hahandle nito yung submit button kung wala laman na, nilagay sa field
         
                const amount = $('input[id=edit_amount]').val();
                const id = $('input[id=id]').val();

                 $.ajax({
         
                     type: 'POST',
                     data: {
                            amount: amount,
                            id: id
                       },
                     url: 'processing/Edit_cashadvance_process.php',
                     async: false,
                     cache: false,
                     success: function(data) {
                      $("#msg").html(data);
                      window.location.reload(true);
                     }
         
                 });
         
             }); 
         });

      /*=====================end  cashadv=====================*/  
     </script>
