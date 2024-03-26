<?php
session_start();
include "include/validation.php";
?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DASHBOARD</title>

    <?php
    include "include/header.php";
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/modalvalidate.js"></script>
    <style>
        .swal2-popup {
            font-size: 1.6rem;
        }
    </style>
</head>

<body>

<div id="wrapper">

    <!-- Navbar -->
    <?php
    include('include/sidebar.php');
    ?>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">

            <?php
            include 'include/navbar.php';
            ?>

        </div>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Dashboard</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="home.php">Home</a>
                    </li>
                    <li class="active">
                        <strong>Dashboard</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">

            <div align="left">
                <button type="button" id="add_button" class="btn btn-primary add-user"data-toggle="modal" data-target="#studentaddmodal"><i class="fa fa-plus">&nbsp;</i> Add Member </button>
            </div>
            <div class="modal fade" id="studentaddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add a new user</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" class="profile-form" id="modalForm" enctype='multipart/form-data'  action="">

                            <div class="modal-body">
                                <p id="message"></p>
                                <div class="form-group">
                                    <label class="small mb-1" for="name">First name</label>
                                    <input class="form-control" id="name" name = 'name' type="text" placeholder="Enter your first name" value="">
                                    <div class="error" id="error">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="small mb-1" for="lastname">Last name</label>
                                    <input class="form-control" id="lastname" name = 'lastname'  type="text" placeholder="Enter your last name" value="">
                                    <div class="error">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="small mb-1" for="email">Email address</label>
                                    <input class="form-control" id="email" name = 'email' type="email" placeholder="Enter your email address" value="">
                                    <div class="error">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="small mb-1" for="birthday">Birthday</label>
                                    <input class="form-control" id="birthday" name = 'birthday' type="date" value="">
                                    <div class="error">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="small mb-1" for="password">New password</label>
                                    <input class="form-control" id="password" type="password" name="password" placeholder="Enter your new password" value="">
                                    <div class="error">
                                    </div>
                                </div>
                                <input type="hidden" name="page" value="display">

                                <div class="form-group">
                                    <label class="small mb-1" for="confirmPassword">Confirm new password</label>
                                    <input class="form-control" id="confirmPassword" type="password" name="confirmPassword" placeholder="Enter your new password" value="">
                                    <div class="error">
                                    </div>
                                </div>
                                <input type="hidden" name="page" value="display">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btn_close">Close</button>
                                <button type="button" name="insertdata" id="insertdata" class="btn btn-primary">Save Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">


                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="memListTable" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Birthday</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Birthday</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
                            <input type="hidden" name="page" value="display">

                        </div>
                    </div>
                </div>
            </div>


        </div>

        <?php
        include 'include/footer.php';
        ?>

    </div>
</div>




<?php
include "include/scripts.php";
?>

<script>
$(document).ready(function (){
    var dataTable = $('#memListTable').DataTable({
        "processing":true,
        "serverSide": true,
        "ajax":{
            url: 'backend/fetchTable.php',
            method:"post",
        }
    });

    $(document).on('click', '.delete', function(){
        var member_id = $(this).attr("id");
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this user!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true ,
            customClass: {
                popup: 'custom-swal-popup' // Apply custom class for styling
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'backend/delete.php?deleteid=' + member_id;
            }
        });
/*
        if(confirm("Are you sure you want to delete this user?"))
        {
            $.ajax({
                url:'backend/delete.php?deleteid=' + member_id,
                method:"POST",
                data:{member_id:member_id},
                success:function(data)
                {
                    dataTable.ajax.reload();
                }
            });
        }
        else
        {
            return false;
        }*/
    });

    var shown = '<?php
        if(isset($_SESSION['has_shown'])){
            echo $_SESSION['has_shown'];
        }
        else{
            echo 'false';
        }
        ?>';


    if (shown !== 'true') {
        setTimeout(function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 4000
            };
            toastr.success('Welcome <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>', 'Intership project');

            // ruajme ne variablen has_shown true ne momentin qe ekzekutohet nje here
            <?php $_SESSION['has_shown'] = 'true'; ?>
        }, 1300);
    }


});

</script>

</body>

</html>
