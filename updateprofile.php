<?php
session_start();

if (!isset($_SESSION['email']) && !isset($_SESSION['id'])) {
    header("Location: login.php");
} else if (empty($_SESSION['email']) || empty($_SESSION['id'])){
    header("Location: login.php");
}

include "include/conn.php";

$query_user_data = "SELECT *
                    FROM users
                    WHERE id = '".mysqli_real_escape_string($conn, $_SESSION['id'])."' ";

$result_user_data = mysqli_query($conn, $query_user_data);

if(!$result_user_data){
    echo "Error";
    exit;
}
$data = mysqli_fetch_assoc($result_user_data);



$validationErrors = ['email' => '', 'name' => '', 'lastname' => '', 'password' => '',
    'confirmPassword' => '', 'agree-term' => '', 'signup' => '', 'birthday' => '', 'picture' => ''];
if (isset($_SESSION['profile_form_validations'])) {
    $validationErrors = array_merge($validationErrors, $_SESSION['profile_form_validations']);
    unset($_SESSION['profile_form_validations']);
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Update Profile</title>

    <?php
    include "include/header.php";
    ?>


</head>

<body class="">

<?php
include "include/menu.php";
?>

            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Update profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="profile.php">Profile</a>
                        </li>
                        <li class="active">
                            <strong>Update Profile</strong>
                        </li>
                    </ol>
                </div>
<!--                <div class="col-sm-8">
                    <div class="title-action">
                        <a href="" class="btn btn-primary">This is action area</a>
                    </div>
                </div>-->
            </div>

            <div class="wrapper wrapper-content animated fadeInRightBig">
                <div class="container px-4 mt-4">

                    <form method="POST" class="profile-form" id="profile-form" enctype='multipart/form-data' action="backend/profile_request.php">
                        <br><br><br>
                        <div class="row">
                            <div class="col-xl-4">
                                <!-- Profile picture card-->
                                <div class="card mb-4 mb-xl-0">
                                    <h3 class="font-bold">Profile Picture</h3>
                                    <div class="card-body text-center">
                                        <!-- Profile picture image-->
                                        <label for="picture" class="upload-file">
                                            <img class="img-account-profile rounded-circle mb-2 photo-src" src="<?=$data['picture']?>" alt="" style="width: 300px; height: 300px; object-fit: cover">
                                            <br><br>
                                            <!-- Profile picture help block-->
                                            <input type="file" id="picture" name="picture" class="form-control m-t" accept="image/*">
                                        </label>
                                        <br>
                                        <div class="error">
                                            <?php echo $validationErrors['picture']; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xl-8">
                                <!-- Account details card-->
                                <div class="card mb-4">
                                    <br><br>
                                    <h3 class="font-bold">Profile Details</h3><br>
                                    <div class="card-body">
                                            <!-- Form Row-->
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="small mb-1 " for="name">First name</label>
                                                    <input class="form-control m-t" id="name" name = 'name' type="text" placeholder="Enter your first name" value="<?= $data['name'] ?>">
                                                    <div class="error">
                                                        <?php echo $validationErrors['name']; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small mb-1" for="lastname">Last name</label>
                                                    <input class="form-control m-t" id="lastname" name = 'lastname'  type="text" placeholder="Enter your last name" value="<?= $data['lastname'] ?>">
                                                    <div class="error">
                                                        <?php echo $validationErrors['lastname']; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Form Group (email address)-->
                                            <div class="mb-3">
                                                <br>
                                                <label class="small mb-1" for="email">Email address</label>
                                                <input class="form-control m-t" id="email" name = 'email' type="email" placeholder="Enter your email address" value="<?= $data['email'] ?>">
                                                <div class="error">
                                                    <?php echo $validationErrors['email']; ?>
                                                </div>
                                            </div>

                                        <!-- Form Group (role)-->
                                        <div class="mb-3">
                                            <br>
                                            <label class="small mb-1" for="role">Role</label>
                                            <input class="form-control m-t" id="role" name = 'role' type="text" value="<?= $data['role'] ?>" readonly>

                                        </div>

                                            <!-- Form Group (birthday)-->

                                            <div class="mb-3">
                                                <br>
                                                <label class="small mb-1" for="birthday">Birthday</label>
                                                <input class="form-control m-t" id="birthday" name = 'birthday' type="date" value="<?= $data['birthday'] ?>">
                                                <div class="error">
                                                    <?php echo $validationErrors['birthday']; ?>
                                                </div>
                                            </div>

                                            <!-- Form Row-->
                                            <div class="row gx-3 mb-3">
                                                <!-- Form Group (phone number)-->
                                                <div class="col-md-6">
                                                    <br>
                                                    <label class="small mb-1" for="password">New password</label>
                                                    <input class="form-control m-t" id="password" type="password" name="password" placeholder="Enter your new password" value="">
                                                    <div class="error">
                                                        <?php echo $validationErrors['password']; ?>
                                                    </div>
                                                </div>
                                                <!-- Form Group (birthday)-->
                                                <div class="col-md-6">
                                                    <br>
                                                    <label class="small mb-1" for="confirmPassword">Confirm new password</label>
                                                    <input class="form-control m-t" id="confirmPassword" type="password" name="confirmPassword" placeholder="Enter your new password" value="">
                                                    <div class="error">
                                                        <?php echo $validationErrors['confirmPassword']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Save changes button-->
                                            <input type="submit" name="edit" id="edit" class="btn btn-primary m-t" value="Save"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
