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
    <style>
        .swal2-popup {
            font-size: 1.6rem;
        }
    </style>
</head>

<body class="pace-done">

<div id="wrapper">

    <!-- Navbar -->
    <?php
    include 'include/sidebar.php';
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
                <button type="button" id="add_button" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;</i> Add Member
                </button>
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

            <div class="modal inmodal" id="myModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content animated bounceInRight">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" id="btn_dismiss"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Add a new user</h4>
                        </div>

                        <div class="modal-body">
                            <form method="POST" class="profile-form" id="modalForm" enctype='multipart/form-data'>

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
                                        <label class="small mb-1" for="role">Role</label>
                                        <select class="form-control" name="role" id="role">
                                            <option value="admin">Admin</option>
                                            <option value="user" selected>User</option>
                                        </select>
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
                                    <input type="hidden" name="member_id" id="member_id" />
                                    <input type="hidden" name="operation" id="operation" />
                                    <button type="button" class="btn btn-white" data-dismiss="modal" id="btn_close">Close</button>
                                    <input type="submit" name="insertdata" id="insertdata" class="btn btn-primary" value="Save" />
                                </div>

                            </form>

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
    let dataTable;
    $(document).ready(function (){
        dataTable = $('#memListTable').DataTable({
            "processing":true,
            "serverSide": true,
            "ajax":{
                url: '../inspina/backend/actionTable.php',
                method:"POST",
                data: function (data){
                    data['operation'] = 'Insert';
                }
            }
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

    $(document).on('click', '#btn_close', function () {
        closeModal();
    })

    $(document).on('click', '#btn_dismiss', function () {
        closeModal();
    });

    $(document).on('submit', '#modalForm', function(event) {
        $('input').on('input', function () {
            // Enable the button when a change occurs
            $('#insertdata').prop('disabled', false);
            $('.error').remove();
        });

        event.preventDefault();
        var id = $('#id').val();
        var name = $('#name').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var birthday = $('#birthday').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();
        var isValid = true;
        const numbers = /[0-9]/;
        const regularExpression = /^(?=.*[a-zA-Z])(?=.*[0-9!@#$%^&*]).{8,16}$/;
        var operation = $('#operation').val();


        if (name === "") {
            $('#name').after('<div class="error">Please enter your name</div>');
            isValid = false;
        } else if (numbers.test(name)) {
            $('#name').after('<div class="error">Name must contain only letters</div>');
            isValid = false;
        }

        if (lastname === "") {
            $('#lastname').after('<div class="error">Please enter your lastname</div>');
            isValid = false;
        } else if (numbers.test(lastname)) {
            $('#lastname').after('<div class="error">Lastname must contain only letters</div>');
            isValid = false;
        }


        if (email === "") {
            $('#email').after('<div class="error">Please enter your email</div>');
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
            $('#email').after('<div class="error">Please enter a valid email</div>');
            isValid = false;
        }


        if (birthday === "") {
            $('#birthday').after('<div class="error">Please enter your birthday</div>');
            isValid = false;
        }
        else if(getAge(birthday) < 18){
            $('#birthday').after('<div class="error">You should be over 18</div>');
            isValid = false;
        }

        if (operation === "Save") {
            if (password === "") {
                $('#password').after('<div class="error">Please enter your password</div>');
                isValid = false;
            }else if (password.length < 8) {
                $('#password').after('<div class="error">Password is expected to be 8 characters</div>');
                isValid = false;
            } else if (!regularExpression.test(password)) {
                $('#password').after('<div class="error">Password should contain atleast one number, one special character and one letter</div>');
                isValid = false;
            }
            if (confirmPassword === "") {
                $('#confirmPassword').after('<div class="error">Please confirm your password</div>');
                isValid = false;
            }
        }
        if (operation === "Edit") {
            if (password !== "") {
                if (password.length < 8) {
                    $('#password').after('<div class="error">Password is expected to be 8 characters</div>');
                    isValid = false;
                } else if (!regularExpression.test(password)) {
                    $('#password').after('<div class="error">Password should contain atleast one number, one special character and one letter</div>');
                    isValid = false;
                }
            }
        }

        if (password !== confirmPassword) {
            $('#confirmPassword').after('<div class="error">Password do not match</div>');
            isValid = false;
        }


        if(isValid){
            $.ajax({
                url: '../inspina/backend/actionTable.php',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success:function (data){
                    if (data.includes('Email already exists!')) {
                        $('#email').after('<div class="error">' + data + '</div>');
                        $('#myModal').modal('show');
                    } else {
                        $('#message').html('<div class="error" style="color: green!important;">' + data + '</div>');
                    }
                    closeModal();
                }
            })
        }
    });

    $(document).on('click', '.update-user-btn', function(e){
        e.preventDefault();
        var member_id = $(this).attr("id");
        var tr = $(this).closest('tr');
        var data = dataTable.rows(tr).data();
        $('#id').val(data[0][0]);
        $('#name').val(data[0][1]);
        $('#lastname').val(data[0][2]);
        $('#email').val(data[0][3]);
        $('#birthday').val(data[0][4]);
        $('.modal-title').text("Edit Member Details");
        $('#member_id').val(member_id);
        $('#insertdata').val("Update");
        $('#operation').val("Edit");
        openModal();
    });

    $(document).on('click', '#add_button' ,function(){
        openModal();
    })

    $(document).on('click', '.delete-user-btn', function(){
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
                popup: 'custom-swal-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(member_id);
                var formData = new FormData();
                formData.append('member_id', member_id);
                formData.append('operation', 'Delete');
                $.ajax({
                    url: '../inspina/backend/actionTable.php',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        dataTable.ajax.reload();
                        console.log(response);
                    },

                });
            }
        });
    });

    function closeModal(){
        $('#modalForm')[0].reset();
        $('#message').html('');
        $('.error').remove();
        $('#myModal').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        $('.modal-title').text("Add New Details");
        $('#insertdata').val("Save");
        $('#operation').val("Save");
        dataTable.ajax.reload();
    }

    function openModal(){
        $('body').addClass('modal-open').append('<div class="modal-backdrop in"></div>');
        $('#myModal').show();
    }

    function getAge(DOB) {
        var today = new Date();
        var birthDate = new Date(DOB);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

</script>

</body>

</html>
