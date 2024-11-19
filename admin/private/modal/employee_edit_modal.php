<!-- Edit Employee Modal -->
<div class="modal fade" id="edit">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title"><b>Edit Employee</b></h4>
         </div>
         <form method="POST" enctype="multipart/form-data">
            <div class="modal-body">
               <input type="hidden" class="form-control" id="emp_id" name="emp_id" required="">
               <div id="msg"></div>
               <div class="form-row">
                  <div class="form-group col-md-5">
                     <label for="Firstname">Firstname</label>
                     <input type="text" class="form-control" id="edit_firstname" name="first_name">
                  </div>
                  <div class="form-group col-md-2">
                     <label for="Middlename">Middlename</label>
                     <input type="text" class="form-control" id="edit_middlename" name="middle_name">
                  </div>
                  <div class="form-group col-md-5">
                     <label for="Lastname">Lastname</label>
                     <input type="text" class="form-control" id="edit_lastname" name="last_name" required="">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-4">
                     <label for="Barangay">Barangay</label>
                     <input type="text" class="form-control" id="edit_barangay" name="barangay">
                  </div>
                  <div class="form-group col-md-4">
                     <label for="Municipality">Municipality</label>
                     <input type="text" class="form-control" id="edit_municipality" name="municipality">
                  </div>
                  <div class="form-group col-md-4">
                     <label for="City">City</label>
                     <input type="text" class="form-control" id="edit_city" name="city">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Birthday">Birthday</label>
                     <input type="date" class="form-control" id="edit_birthdate" name="birth_date">
                  </div>
                  <div class="form-group col-md-6">
                     <label for="MobileNumber">Mobile Number</label>
                     <input type="text" class="form-control" id="edit_mobilenumber" name="Mobile_number" maxlength="11">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Gender">Gender</label>
                     <select class="form-control" id="edit_gender" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="Position">Position</label>
                     <select class="form-control" id="edit_positionid" name="position_id">
                     <?php
                        include '../connection/db_conn.php';
                        $sql = "SELECT * FROM employee_position";
                        $query = $conn->query($sql);
                        while ($prow = $query->fetch_assoc()) {
                           echo "<option value='".htmlspecialchars($prow['id'])."'>".htmlspecialchars($prow['emp_position'])."</option>";
                        }
                     ?>
                     </select>
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="MaritalStatus">Marital Status</label>
                     <select class="form-control" id="edit_maritalstatus" name="marital_status">
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widow/er">Widow/er</option>
                        <option value="Annulled">Annulled</option>
                        <option value="Legally Separated">Legally Separated</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="Schedule">Schedule</label>
                     <select class="form-control" id="edit_scheduleid" name="schedule_id">
                     <?php
                        include '../connection/db_conn.php';
                        $sql = "SELECT * FROM employee_schedule";
                        $query = $conn->query($sql);
                        while ($srow = $query->fetch_assoc()) {
                           echo "<option value='".htmlspecialchars($srow['id'])."'>".htmlspecialchars($srow['time_in']).' - '.htmlspecialchars($srow['time_out'])."</option>";
                        }
                     ?>
                     </select>
                  </div>
               </div>
               <div class="form-group col-md-12">
                  <label for="Profile">Profile</label>
                  <input type="file" id="edit_profilepic" class="form-control" name="profile_pic">
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

<script>
   $(document).ready(function () {
      $('#editemp').on('click', function (e) {
         e.preventDefault();

         const data = new FormData();
         data.append('emp_id', $('#emp_id').val());
         data.append('first_name', $('#edit_firstname').val());
         data.append('middle_name', $('#edit_middlename').val());
         data.append('last_name', $('#edit_lastname').val());
         data.append('barangay', $('#edit_barangay').val());
         data.append('municipality', $('#edit_municipality').val());
         data.append('city', $('#edit_city').val());
         data.append('birth_date', $('#edit_birthdate').val());
         data.append('Mobile_number', $('#edit_mobilenumber').val());
         data.append('gender', $('#edit_gender').val());
         data.append('position_id', $('#edit_positionid').val());
         data.append('marital_status', $('#edit_maritalstatus').val());
         data.append('schedule_id', $('#edit_scheduleid').val());
         data.append('profile_pic', $('#edit_profilepic')[0].files[0]);

         $.ajax({
            url: 'processing/Edit_employee_process.php',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
               $('#msg').html(response);
               setTimeout(() => location.reload(), 200);
            },
            error: function () {
               console.error('Failed to update employee.');
            },
         });
      });
   });
</script>
