<!-- Add -->
<div class="modal fade" id="edit">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><b>Edit Employee</b></h4>
         </div>
         <form method="POST" enctype="multipart/form-data">
            <div class="modal-body">
               <input type="hidden" class="form-control" id="emp_id" alt="emp_id" name="emp_id" autocomplete="off" required="">
               <div id="msg"></div>
               <div class="form-row">
                  <div class="form-group col-md-5">
                     <label for="Firstname">Firstname</label>
                     <input type="text" class="form-control" id="edit_firstname" alt="first_name" name="first_name">
                  </div>
                  <div class="form-group col-md-2">
                     <label for="Middlename">Middlename</label>
                     <input type="text" class="form-control" id="edit_middlename" alt="middle_name" name="middle_name" autocomplete="off">
                  </div>
                  <div class="form-group col-md-5">
                     <label for="Lastname">Lastname</label>
                     <input type="text" class="form-control" id="edit_lastname" alt="last_name" name="last_name" autocomplete="off" required="">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-12">
                     <label for="CompleteAddress">Complete Address</label>
                     <input type="text" class="form-control" id="edit_address" alt="complete_address" name="complete_address" autocomplete="off">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Birthday">Birthday</label>
                     <input type="date" class="form-control" id="edit_birthdate" alt="birth_date" name="birth_date" autocomplete="off">
                  </div>
                  <div class="form-group col-md-6">
                     <label for="MobileNumber">Mobile Number</label>
                     <input type="text" class="form-control" id="edit_mobilenumber" alt="Mobile_number" name="Mobile_number"  maxlength="11" maxlength="11" autocomplete="off">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Birthday">Gender</label>
                     <select class="form-control gender" id="edit_gender"  name="gender">
                        <option value="Male" selected>Male</option>
                        <option value="Female">Female</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="Position">Position</label>
                     <select class="form-control position_id"  id="edit_positionid"  name="position_id" required>
                     <?php
                        include '../connection/db_conn.php';
                         $sql = "SELECT * FROM employee_position";
                         $query = $conn->query($sql);
                         while($prow = $query->fetch_assoc()){
                           echo "
                             <option value='".htmlspecialchars($prow['id'])."'>".htmlspecialchars($prow['emp_position'])."</option>
                           ";
                         }
                        ?>
                     </select>
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Birthday">Marital Status</label>
                     <select class="form-control marital_status" id="edit_maritalstatus"  name="marital_status">
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widow/er">Widow/er</option>
                        <option value="Married">Anulled</option>
                        <option value="Legally Separated">Legally Separated</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="MobileNumber">Schedule</label>
                     <select class="form-control schedule_id" id="edit_scheduleid" name="schedule_id">
                     <?php
                        include '../connection/db_conn.php';
                         $sql = "SELECT * FROM employee_schedule";
                         $query = $conn->query($sql);
                         while($srow = $query->fetch_assoc()){
                           echo "
                             <option value='".htmlspecialchars($srow['id'])."'>".htmlspecialchars($srow['time_in']).' - '.htmlspecialchars($srow['time_out'])."</option>
                           ";
                         }
                        ?>
                     </select>
                  </div>
               </div>
               <div class="form-group col-md-12">
                  <label for="MobileNumber">Profile</label>
                  <input type="file" id="edit_profilepic" class="form-control" alt="profile_pic" name ="profile_pic"><br>
               </div>
               <div class="form-group col-md-0">
                  <!--                      <label for="MobileNumber">Image profile</label><br>
                     <img src="" class="img img-thumbnail" style="max-height: 150px!important" id="edit_profilepic" > -->
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" id="editemp">Update</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!--end Add Modal -->
<script type="text/javascript">
   $(document).ready(function() {
   
    document.getElementById("editemp").addEventListener("click", (e) =>{
       e.preventDefault();
   
      const first_name = document.querySelector('input[id=edit_firstname]').value;
      const middle_name = document.querySelector('input[id=edit_middlename]').value;
      const last_name = document.querySelector('input[id=edit_lastname]').value;
      const complete_address = document.querySelector('input[id=edit_address]').value;
      const birth_date = document.querySelector('input[id=edit_birthdate]').value;
      const Mobile_number = document.querySelector('input[id=edit_mobilenumber]').value;
      const gender = ($('.gender option:selected').val());
      const position_id = ($('.position_id option:selected').val());
      const marital_status = ($('.marital_status option:selected').val());
      const schedule_id = ($('.schedule_id option:selected').val());
      const emp_id = document.querySelector('input[id=emp_id]').value;
   
   
         var delay = 100;
               var data = new FormData(this.form);
               data.append('first_name', first_name);
               data.append('middle_name', middle_name);
               data.append('last_name', last_name);
               data.append('complete_address', complete_address);
               data.append('birth_date', birth_date);
               data.append('Mobile_number', Mobile_number);
               data.append('gender', gender);
               data.append('position_id', position_id);
               data.append('marital_status', marital_status);
               data.append('schedule_id', schedule_id);
               data.append('profile_pic', $('#edit_profilepic')[0].files[0]);
               data.append('emp_id', emp_id);
   
            $.ajax({
                url: 'processing/Edit_employee_process.php',
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