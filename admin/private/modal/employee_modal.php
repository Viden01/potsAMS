<!-- Add Modal -->
<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Employee</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="employeeForm">
                <div class="modal-body">
                    <div id="msg"></div>
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>Firstname</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Middlename</label>
                            <input type="text" class="form-control" name="middle_name">
                        </div>
                        <div class="form-group col-md-5">
                            <label>Lastname</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Barangay</label>
                            <input type="text" class="form-control" name="barangay" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Municipality</label>
                            <input type="text" class="form-control" name="municipality" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>City</label>
                            <input type="text" class="form-control" name="city" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Birthday</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Mobile Number</label>
                            <input type="text" class="form-control" id="Mobile_number" name="Mobile_number" maxlength="11" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Gender</label>
                            <select class="form-control" name="gender">
                                <option value="Male" selected>Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Position</label>
                            <select class="form-control" name="position_id" required>
                                <?php
                                include '../connection/db_conn.php';
                                $sql = "SELECT * FROM employee_position";
                                $query = $conn->query($sql);
                                while ($row = $query->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['emp_position']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Marital Status</label>
                            <select class="form-control" name="marital_status">
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widow/er">Widow/er</option>
                                <option value="Anulled">Anulled</option>
                                <option value="Legally Separated">Legally Separated</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Schedule</label>
                            <select class="form-control" name="schedule_id">
                                <?php
                                $sql = "SELECT * FROM employee_schedule";
                                $query = $conn->query($sql);
                                while ($row = $query->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['time_in']} - {$row['time_out']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Profile</label>
                            <input type="file" name="profile_pic">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('employeeForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = new FormData(this);
        $.ajax({
            url: 'processing/employee_process.php',
            type: 'POST',
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#msg').html(response);
                if (response.includes('successfully')) {
                    setTimeout(() => location.reload(), 2000);
                }
            }
        });
    });
});
</script>
