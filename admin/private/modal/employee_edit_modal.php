<!-- Edit -->
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
                  <div class="form-group col-md-4">
                     <label for="Barangay">Barangay</label>
                     <input type="text" class="form-control" id="edit_barangay" name="barangay" autocomplete="off">
                  </div>
                  <div class="form-group col-md-4">
                     <label for="Municipality">Municipality</label>
                     <input type="text" class="form-control" id="edit_municipality" name="municipality" autocomplete="off">
                  </div>
                  <div class="form-group col-md-4">
                     <label for="City">City</label>
                     <input type="text" class="form-control" id="edit_city" name="city" autocomplete="off">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Birthday">Birthday</label>
                     <input type="date" class="form-control" id="edit_birthdate" alt="birth_date" name="birth_date" autocomplete="off">
                  </div>
                  <div class="form-group col-md-6">
                     <label for="MobileNumber">Mobile Number</label>
                     <input type="text" class="form-control" id="edit_mobilenumber" alt="Mobile_number" name="Mobile_number" maxlength="11" autocomplete="off">
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
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" id="editemp">Update</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!--end Edit Modal -->
