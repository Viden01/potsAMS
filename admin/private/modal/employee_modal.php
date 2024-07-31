<!-- Add -->
<div class="modal fade" id="exampleModal">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title"><b>Add Employee</b></h4>
          </div>
         <form method="POST" enctype="multipart/form-data" id="employeeForm">
            <div class="modal-body">
               <div id="msg"></div>
               <div class="form-row">
                  <div class="form-group col-md-5">
                     <label for="Firstname">Firstname</label>
                     <input type="text" class="form-control" alt="first_name" name="first_name" autocomplete="off" required="">
                  </div>
                  <div class="form-group col-md-2">
                     <label for="Middlename">Middlename</label>
                     <input type="text" class="form-control" alt="middle_name" name="middle_name" autocomplete="off">
                  </div>
                  <div class="form-group col-md-5">
                     <label for="Lastname">Lastname</label>
                     <input type="text" class="form-control" alt="last_name" name="last_name" autocomplete="off" required="">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-12">
                     <label for="CompleteAddress">Complete Address</label>
                     <input type="text" class="form-control" alt="complete_address" name="complete_address" autocomplete="off">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Birthday">Birthday</label>
                     <input type="date" class="form-control" alt="birth_date" name="birth_date" id="birth_date" autocomplete="off" onchange="calculateAge()">
                  </div>
                  <div class="form-group col-md-6">
                     <label for="Age">Age</label>
                     <input type="text" class="form-control" alt="age" name="age" id="age" readonly>
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="MobileNumber">Mobile Number</label>
                     <input type="text" class="form-control" id="Mobile_number" name="Mobile_number" maxlength="11" autocomplete="off" required>
                  </div>
                  <div class="form-group col-md-6">
                     <label for="Gender">Gender</label>
                     <select class="form-control" id="gender" name="gender">
                        <option value="Male" selected>Male</option>
                        <option value="Female">Female</option>
                     </select>
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Position">Position</label>
                     <select class="form-control" id="position_id" name="position_id" required>
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
                  <div class="form-group col-md-6">
                     <label for="MaritalStatus">Marital Status</label>
                     <select class="form-control" id="marital_status" name="marital_status">
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widow/er">Widow/er</option>
                        <option value="Anulled">Anulled</option>
                        <option value="Legally Separated">Legally Separated</option>
                     </select>
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="Schedule">Schedule</label>
                     <select class="form-control" id="schedule_id" name="schedule_id">
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
                  <div class="form-group col-md-6">
                     <label for="Profile">Profile</label>
                     <input type="file" id="profile_pic" name="profile_pic">
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary" id="addemp">Add</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!--end Add Modal -->

<script type="text/javascript">
$(document).ready(function() {
    // Set max date for the birthday input to today's date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('birth_date').setAttribute('max', today);

    document.getElementById("employeeForm").addEventListener("submit", (e) =>{
        e.preventDefault(); // Prevent default form submission

        const mobileNumber = document.getElementById('Mobile_number').value;
        const mobileNumberPattern = /^\d{11}$/;
        if (!mobileNumberPattern.test(mobileNumber)) {
            alert('Please enter exactly 11 digits for the mobile number.');
            return false;
        }

        const form = document.getElementById('employeeForm');
        var data = new FormData(form);

        $.ajax({
            url: 'processing/employee_process.php',
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.startsWith("warning:")) {
                    $('#msg').html('<div class="alert alert-warning">' + response.substring(8) + '</div>');
                } else {
                    $('#msg').html('<div class="alert alert-success">Employee added successfully!</div>');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Failed: " + status + " " + error);
            }
        });
    });
});

function calculateAge() {
    const birthDateInput = document.getElementById('birth_date').value;
    if (birthDateInput) {
        const birthDate = new Date(birthDateInput);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        document.getElementById('age').value = age;
    } else {
        document.getElementById('age').value = '';
    }
}
</script>
