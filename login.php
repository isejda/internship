<?php
session_start();
$validationErrors = ['email' => '', 'password' => ''];
if (isset($_SESSION['login_form_validations'])) {
    $validationErrors = array_merge($validationErrors, $_SESSION['login_form_validations']);
    unset($_SESSION['login_form_validations']);
}

if(isset($_SESSION['id'])){
    header("Location:profile.php");
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Login</title>

    <?php
    include "include/header.php";
    ?>

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">HEY</h1>

            </div>
            <h3>Welcome</h3>
            <p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
                <!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
            </p>
            <p>Login in now </p>
            <form method="POST" class="register-form" id="login-form" action="backend/log_request.php">
                <div class="form-group">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" required="">
                    <div class="errorMessage">
                        <?php echo $validationErrors['email']; ?>
                    </div>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="">
                    <div class="errorMessage">
                        <?php echo $validationErrors['password']; ?>
                    </div>
                </div>
                <div class="form-group form-button">
                    <input type="submit" name="signin" id="signin" class="btn btn-primary block full-width m-b" value="Log in"/>
                </div>
                <a href="#"><small>Forgot password?</small></a>
                <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register.php">Create an account</a>
            </form>
            <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
