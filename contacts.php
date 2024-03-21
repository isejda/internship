<?php
session_start();

include "include/conn.php";
include "include/validation.php";

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Admin's Dashboard</title>

    <?php
    include "include/header.php";
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/modalvalidate.js"></script>



</head>

<body>
<div id="wrapper">
    <!-- Navbar -->
    <?php
    include('include/sidebar.php');
    ?>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <?php
        include 'include/navbar.php';
        ?>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-9">
                <h2>Contacts</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="home.php">Home</a>
                    </li>

                    <li class="active">
                        <strong>Clients</strong>
                    </li>

                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content">
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
                                        <form method="POST" class="profile-form" id="modalForm" enctype='multipart/form-data'  action="">

                                            <div class="modal-body">
                                                <p id="message"></p>
                                                <div class="form-group">
                                                    <label class="small mb-1" for="name">First name</label>
                                                    <input class="form-control" id="name" name = 'name' type="text" placeholder="Enter your first name" value="">
                                                    <div class="error" id="error">
                                                        <?php echo $validationErrors['name']; ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="small mb-1" for="lastname">Last name</label>
                                                    <input class="form-control" id="lastname" name = 'lastname'  type="text" placeholder="Enter your last name" value="">
                                                    <div class="error">
                                                        <?php echo $validationErrors['lastname']; ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="small mb-1" for="email">Email address</label>
                                                    <input class="form-control" id="email" name = 'email' type="email" placeholder="Enter your email address" value="">
                                                    <div class="error">
                                                        <?php echo $validationErrors['email']; ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="small mb-1" for="birthday">Birthday</label>
                                                    <input class="form-control" id="birthday" name = 'birthday' type="date" value="">
                                                    <div class="error">
                                                        <?php echo $validationErrors['birthday']; ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="small mb-1" for="password">New password</label>
                                                    <input class="form-control" id="password" type="password" name="password" placeholder="Enter your new password" value="">
                                                    <div class="error">
                                                        <?php echo $validationErrors['password']; ?>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="page" value="display">

                                                <div class="form-group">
                                                    <label class="small mb-1" for="confirmPassword">Confirm new password</label>
                                                    <input class="form-control" id="confirmPassword" type="password" name="confirmPassword" placeholder="Enter your new password" value="">
                                                    <div class="error">
                                                        <?php echo $validationErrors['confirmPassword']; ?>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="page" value="display">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btn_close">Close</button>
                                                <button type="button" name="insertdata" id="insertdata" class="btn btn-success">Save Data</button>
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
                            <img alt="image" class="img-circle m-t-xs img-responsive" src="inspina/'.$picture.'">
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
                            <img alt="image" class="img-circle m-t-xs img-responsive" src="inspina/'.$picture.'">
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
                <?php
                include 'include/footer.php';
                ?>
            </div>
        </div>

    </div>
</div>

<?php
include "include/scripts.php";
?>

<script>
    $(document).ready(function() {
//operatori i turnerit
        // nese variable has_shown eshte set dhe ekziston
        // nese ekziston kthen vleren true qe merr poshte kur ekzekutohe, ne te kundert behet false

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
