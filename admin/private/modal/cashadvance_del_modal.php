<!-- Add -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"><b><span class="date">Delete Cash Advance</span> </span></b></h4>
          	</div>
          	<div class="modal-body">
             <div id="msg"></div>
            	<form class="form-horizontal" method="POST">
                <input type="hidden" id="del_id" name="id">
                 <h4 class=""><b><span class="date">Do you want to delete Cash Advance of ? </span>" <span id="view_employeename"></span> "</b></h4>
                <div class="form-group">
                    <label for="time_out" class="col-sm-3 control-label">Amount</label>
                    <div class="col-sm-9">
                      <div>
                        <input id="del_amount" alt="amount"  class="form-control" type="text" readonly="" />
                      </div>
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> No</button>
            	<button type="button" class="btn btn-success btn-flat" name="add" id="delcash">Yes</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
     <script type="text/javascript">
      /*=====================start cashadv=====================*/  
       $(document).ready(function() {
           $('#delcash').click(function(e) {
              e.preventDefault();///i hahandle nito yung submit button kung wala laman na, nilagay sa field

                const id = $('input[id=del_id]').val();
                 $.ajax({
         
                     type: 'POST',
                     data: {
                            id: id
                       },
                     url: 'processing/Delete_cashadvance_process.php',
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
