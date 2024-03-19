<?php
session_start();
require_once "include/conn.php";

if (!isset($_SESSION['email']) && !isset($_SESSION['id'])) {
    header("Location: login.php");
} else if (empty($_SESSION['email']) || empty($_SESSION['id'])){
    header("Location: login.php");
}

$validationErrors = ['email' => '', 'name' => '', 'lastname' => '', 'password' => '',
    'confirmPassword' => '', 'agree-term' => '', 'signup' => '', 'birthday' => ''];
if (isset($_SESSION['register_form_validations'])) {
    $validationErrors = array_merge($validationErrors, $_SESSION['register_form_validations']);
    unset($_SESSION['register_form_validations']);
}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Contacts</title>

    <?php
    include "include/header.php";
    ?>


</head>

<body>
<?php
include "include/menu.php";
?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-9">
                    <h2>Contacts</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>

                        <li class="active">
                            <strong>Clients</strong>
                        </li>

                    </ol>
                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="ibox-title">
                <h5>Clients</h5>

                <div class="col-lg-3">
                    <a href="#" class="add-user" data-toggle="modal" data-target="#studentaddmodal"><i class="fa fa-plus"></i></a>
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
                            <form method="POST" class="profile-form" id="profile-form" enctype='multipart/form-data'  action="reg_request.php">

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="small mb-1" for="name">First name</label>
                                        <input class="form-control" id="name" name = 'name' type="text" placeholder="Enter your first name" value="">
                                        <div class="errorMessage">
                                            <?php echo $validationErrors['name']; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="lastname">Last name</label>
                                        <input class="form-control" id="lastname" name = 'lastname'  type="text" placeholder="Enter your last name" value="">
                                        <div class="errorMessage">
                                            <?php echo $validationErrors['lastname']; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="email">Email address</label>
                                        <input class="form-control" id="email" name = 'email' type="email" placeholder="Enter your email address" value="">
                                        <div class="errorMessage">
                                            <?php echo $validationErrors['email']; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="birthday">Birthday</label>
                                        <input class="form-control" id="birthday" name = 'birthday' type="date" value="">
                                        <div class="errorMessage">
                                            <?php echo $validationErrors['birthday']; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="birthday">Role</label>
                                        <select class="form-control" name="role" id="role">
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="password">New password</label>
                                        <input class="form-control" id="password" type="password" name="password" placeholder="Enter your new password" value="">
                                        <div class="errorMessage">
                                            <?php echo $validationErrors['password']; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="confirmPassword">Confirm new password</label>
                                        <input class="form-control" id="confirmPassword" type="password" name="confirmPassword" placeholder="Enter your new password" value="">
                                        <div class="errorMessage">
                                            <?php echo $validationErrors['confirmPassword']; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="checkbox" name="agree-term" id="agree-term" class="agree-term"/>
                                        <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in <a href="#" class="term-service">Terms of service</a></label>
                                        <div class="errorMessage">
                                            <?php echo $validationErrors['agree-term']; ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="page" value="display">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="insertdata" id="insertdata" class="btn btn-primary">Save Data</button>

                                    <script>
                                        document.getElementById('insertdata').addEventListener('click', function(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: "You are about to save the data!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes, save it!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.querySelector('form').submit();
                                                }
                                            });
                                        });
                                        function redirectToDisplayPage() {
                                            window.location.href = "display.php";
                                        }
                                    </script>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
            <?php
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);
            if($result){
                while ($row = mysqli_fetch_assoc($result)){
                    if($row['role'] === 'user'){
                        $id = $row['id'];
                        $name = $row['name'];
                        $lastname = $row['lastname'];
                        $email = $row['email'];
                        $birthday = $row['birthday'];
                        $picture = $row['picture'];
                        $role = $row['role'];
                        echo '
            <div class="col-lg-4">
                <div class="contact-box">
                    <a href="update.php?id='.$id.'">
                    <div class="col-sm-4">
                        <div class="text-center">
                            <img alt="image" class="img-circle m-t-xs img-responsive" src="'.$picture.'">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <h3><strong> '.$name.' '.$lastname.'</strong></h3>
                        <p><i class="fa fa-envelope"></i>  '.$email.' </p>
                        <address>
                            Birthday <br>
                            '.$birthday.'<br>
                        </address>
                    </div>
                    <div class="clearfix"></div>
                        </a>
                </div>
            </div>';
                    }


                };
            }
            ?>



        </div>
            <div class="row">

                <div class="ibox-title">
                    <h5>Admins</h5>
                </div>
                <?php
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);
                if($result){
                    while ($row = mysqli_fetch_assoc($result)){
                        if($row['role'] === 'admin'){
                            $id = $row['id'];
                            $name = $row['name'];
                            $lastname = $row['lastname'];
                            $email = $row['email'];
                            $birthday = $row['birthday'];
                            $picture = $row['picture'];
                            $role = $row['role'];
                            echo '
            <div class="col-lg-4">
                <div class="contact-box">
                    <a href="update.php?id='.$id.'">
                    <div class="col-sm-4">
                        <div class="text-center">
                            <img alt="image" class="img-circle m-t-xs img-responsive" src="'.$picture.'">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <h3><strong> '.$name.' '.$lastname.'</strong></h3>
                        <p><i class="fa fa-envelope"></i>  '.$email.' </p>
                        <address>
                            Birthday <br>
                            '.$birthday.'<br>
                        </address>
                    </div>
                    <div class="clearfix"></div>
                        </a>
                </div>
            </div>';
                        }


                    };
                }
                ?>



            </div>

        </div>
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2017
            </div>
        </div>

        </div>
        </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>
