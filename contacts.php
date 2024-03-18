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
                            You can only view admins data<br>
                            but you cant modify them<br>
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


</body>

</html>
