<?php
$request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
}

include('header/head.php');?>
<?php include('header/sidebar_menu.php');?>

<!-- end navbar side -->
<!--  page-wrapper -->
<div id="page-wrapper">

    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12">
           
        </div>
        <!--End Page Header -->
    </div>

    <div class="row">
        <!-- Welcome -->
        <div class="col-lg-12">
            <div class="alert alert-info">
                
            </div>
        </div>
        <!--end  Welcome -->
    </div>

    <!-- Advanced Tables -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add Position</a>
        </div>
        <!-- Modal -->
        <?php include 'modal/position_modal.php'; ?>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <th>Position Title</th>
                        <th>Rate per Hour</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM employee_position";
                        $query = $conn->query($sql);
                        while($row = $query->fetch_assoc()){
                            echo "
                                <tr>
                                    <td>".htmlentities($row['emp_position'])."</td>
                                    <td>".number_format(htmlentities($row['rate_per_hour']), 2)."</td>
                                    <td>". date('M d, Y', strtotime(htmlentities($row['date_added']))) ."</td>
                                    <td>
                                        <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit</button>
                                        <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Delete</button>
                                    </td>
                                </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'modal/position_edit_modal.php'; ?>
    <?php include 'modal/postion_del_modal.php'; ?>
    <script>
    $(function(){
        $('.edit').click(function(e){
            e.preventDefault();
            $('#edit').modal('show');
            var id = $(this).data('id');
            editID(id);
        });

        $('.delete').click(function(e){
            e.preventDefault();
            $('#delete').modal('show');
            var id = $(this).data('id');
            delID(id);
        });
    });

    function editID(id){
        $.ajax({
            type: 'POST',
            url: 'position_row.php',
            data: {id:id},
            dataType: 'json',
            success: function(response){
                $('#id').val(response.id);
                $('#edit_empposition').val(response.emp_position);
                $('#edit_rateperhour').val(response.rate_per_hour);
            }
        });
    }

    function delID(id){
        $.ajax({
            type: 'POST',
            url: 'position_row2.php',
            data: {id:id},
            dataType: 'json',
            success: function(response2){
                $('#del_id').val(response2.id);
                $('#del_perhr').html(response2.emp_position);
            }
        });
    }
    </script>

</div>
<!-- end page-wrapper -->
</div>

<!-- end wrapper -->
<!-- Core Scripts - Include with every page -->
<script src="assets/plugins/jquery-1.10.2.js"></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/plugins/pace/pace.js"></script>
<script src="assets/scripts/siminta.js"></script>
<!-- Page-Level Plugin Scripts-->
<script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>

<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script>

<script src="js/timepicki.js"></script>
<script>
    $('#timepicker1').timepicki();
    $('#timepicker2').timepicki();
    $('.timepicker1').timepicki();
    $('.timepicker2').timepicki();
</script>

</body>
</html>
