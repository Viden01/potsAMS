<!-- Add -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"><b><span class="date">Delete Deduction</span> </span></b></h4>
          	</div>
          	<div class="modal-body">
             <div id="msg"></div>
            	<form class="form-horizontal" method="POST">
                <input type="hidden" id="del_id" name="id">
                 <h4 class=""><b><span class="date">Do you want to delete Deduction  ? </span>" <span id="del_deductionname"></span> "</b></h4>
            	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> No</button>
            	<button type="button" class="btn btn-success btn-flat" name="add" id="deldec">Yes</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
     <script type="text/javascript">
      /*=====================start deldec=====================*/  
       $(document).ready(function() {
           $('#deldec').click(function(e) {
              e.preventDefault();///i hahandle nito yung submit button kung wala laman na, nilagay sa field

                const id = $('input[id=del_id]').val();
                 $.ajax({
         
                     type: 'POST',
                     data: {
                            id: id
                       },
                     url: 'processing/Delete_deduction_process.php',
                     async: false,
                     cache: false,
                     success: function(data) {
                      $("#msg").html(data);
                      window.location.reload(true);
                     }
         
                 });
         
             }); 
         });

      /*=====================end  deldec=====================*/  
     </script>
