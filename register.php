<?php
session_start();
$validationErrors = ['email' => '', 'name' => '', 'lastname' => '', 'password' => '',
    'confirmPassword' => '', 'agree-term' => '', 'signup' => '', 'birthday' => ''];
if (isset($_SESSION['register_form_validations'])) {
    $validationErrors = array_merge($validationErrors, $_SESSION['register_form_validations']);
    unset($_SESSION['register_form_validations']);
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

    <title>INSPINIA | Register</title>

    <?php
    include "include/header.php";
    ?>

</head>

<body class="gray-bg">
    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">JOIN</h1>

            </div>
            <h3>Register</h3>
            <p>Create account to see it in action.</p>
            <form role="form" method="POST" class="register-form" id="register-form" action="backend/reg_request.php">

                <div class="form-group">
                    <input type="text" class="form-control <?php echo !empty($validationErrors['name']) ? 'is-invalid' : ''; ?>" name="name" id="name" placeholder="Your First Name" required value="<?php echo !empty($_POST["name"]) ? $_POST["name"] : ''; ?>">
                    <span class="error">
                            <?php echo $validationErrors['name']; ?>
                    </span>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control <?php echo !empty($validationErrors['lastname']) ? 'is-invalid' : ''; ?>" name="lastname" id="lastname" placeholder="Your Last Name" required="">
                    <span class="error">
                        <?php echo $validationErrors['lastname']; ?>
                    </span>
                </div>

                <div class="form-group">
                    <input type="email" class="form-control <?php echo !empty($validationErrors['email']) ? 'is-invalid' : ''; ?>" name="email" id="email" placeholder="Your Email" required="">
                    <span class="error">
                        <?php echo $validationErrors['email']; ?>
                    </span>
                </div>

                <div class="form-group">
                    <input type="date" name="birthday" id="birthday" class="form-control <?php echo !empty($validationErrors['birthday']) ? 'is-invalid' : ''; ?>" required="">
                    <span class="error">
                        <?php echo $validationErrors['birthday']; ?>
                    </span>
                </div>

                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control <?php echo !empty($validationErrors['password']) ? 'is-invalid' : ''; ?>" placeholder="Password" required="">
                    <span class="error">
                        <?php echo $validationErrors['password']; ?>
                    </span>
                </div>

                <div class="form-group">
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control <?php echo !empty($validationErrors['confirmPassword']) ? 'is-invalid' : ''; ?>" placeholder="Repeat your Password" required="">
                    <span class="error">
                        <?php echo $validationErrors['confirmPassword']; ?>
                    </span>
                </div>

                <div class="form-group">
                    <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" required=""/>
                    <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all
                        statements in <a href="#" class="term-service">Terms of service</a></label>
                    <div class="error">
                        <?php echo $validationErrors['agree-term']; ?>
                    </div>
                </div>
                <input type="submit" name="signup" id="signup" class="btn btn-primary block full-width m-b" value="Register"/>
                <div class="error">
                    <?php echo $validationErrors['signup']; ?>
                </div>
                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="login.php">Login</a>
            </form>
            <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
</body>

</html>
