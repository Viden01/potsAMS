<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b><span class="employee_id">Delete Employee</span></b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST">
                <div id="msg"></div>
            		<input type="hidden" id="del_empid" name="emp_id">
            		<div class="text-center">
	                <h3>Do you want delete this user ? </h3>
	                <h2 style="font-weight: bold;" id="del_employeename"></h2>
	            	</div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> No</button>
            	<button type="submit" class="btn btn-success" name="delete" id="del"><i class="fa fa-trash"></i> Yes</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
<script type="text/javascript">
   $(document).ready(function() {
   
    document.getElementById("del").addEventListener("click", (e) =>{
       e.preventDefault();

      const emp_id = document.querySelector('input[id=del_empid]').value;

         var delay = 100;
               var data = new FormData(this.form);
               data.append('emp_id', emp_id);
   
            $.ajax({
                url: 'processing/Delete_employee_process.php',
                type: "POST",
                data: data,
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