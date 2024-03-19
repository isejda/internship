<?php
session_start();
$id =  $_SESSION['id'];
//print_r($id);
//exit;

require_once "include/conn.php";
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
                            <a href="home.php">Home</a>
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
                            <form method="POST" class="profile-form" id="profile-form" enctype='multipart/form-data'  action="backend/reg_request.php">

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
                                        <label class="small mb-1" for="password">New password</label>
                                        <input class="form-control" id="password" type="password" name="password" placeholder="Enter your new password" value="">
                                        <div class="errorMessage">
                                            <?php echo $validationErrors['password']; ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="page" value="display">

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
include "include/footer.php";
include "include/scripts.php";

?>


</body>

</html>
