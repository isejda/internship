<?php
session_start();

include "include/validation.php";

include "include/conn.php";
//access the parameter from the url
if (!isset($_GET['id'])) {
    header("Location: 404.html");
    exit;
}


$id = $_GET['id'];
//print_r($id);
//exit;

$query_user_data = "SELECT *
                    FROM users
                    WHERE id = $id";

$result_user_data = mysqli_query($conn, $query_user_data);

if (!$result_user_data || mysqli_num_rows($result_user_data) == 0) {
    // Handle error if user data is not found
    header("Location: 404.html");
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Update <?php echo $data['name'];?></title>
    <?php
    include "include/header.php";
    ?>

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

            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-9">
                    <h2>Update Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="home.php">Home</a>
                        </li>
                        <li>
                            <a href="contacts.php">Clients</a>
                        </li>

                        <li class="active">
                            <strong>Update User</strong>
                        </li>
                    </ol>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRightBig">
                    <div class="container px-4 mt-4">

                        <form method="POST" class="profile-form" id="profile-form" enctype='multipart/form-data' action="backend/profile_request.php">
                            <br><br><br>
                            <div class="row">

                                <div class="col-xl-4">
                                    <div class="card mb-4 mb-xl-0">
                                        <h3 class="font-bold">Profile Picture</h3>
                                        <div class="card-body text-center">
                                            <!-- Profile picture image-->
                                            <label for="picture" class="upload-file">
                                                <img id="profile-pic" class="img-account-profile rounded-circle mb-2 photo-src" src="inspina/<?=$data['picture']?>" alt="" style="width: 300px; height: 300px; object-fit: cover">
                                                <br><br>
                                                <!-- Profile picture help block-->
                                                <input type="file" id="picture" name="picture" class="form-control m-t" accept="image/*" onchange="previewImage(event)">
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
                                                <select class="form-control" name="role" id="role">
                                                    <?php
                                                    if($data['role'] === 'admin'){
                                                        echo'                                    
                                    <option value="admin" selected>Admin</option>
                                    <option value="user">User</option>
                                        ';
                                                    }
                                                    else{
                                                        echo'                                    
                                    <option value="admin">Admin</option>
                                    <option value="user" selected>User</option>
                                        ';
                                                    }
                                                    ?>

                                                </select>
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
                                                <input type="hidden" name="page" value="updateusers">
                                                <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']?>">

                                            </div>

                                            <input type="submit" name="edit" id="edit" class="btn btn-primary m-t" value="Save"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                    </div>
                    <button class="btn btn-danger deleteBtn" data-id=" <?php echo $_GET['id']; ?>" style="margin-left: 224px">Delete</button>
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
        // Check if the notification has already been shown
        var shown = '<?php echo isset($_SESSION['has_shown']) ? $_SESSION['has_shown'] : 'false'; ?>';

        if (shown !== 'true') {
        setTimeout(function() {
        toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        timeOut: 4000
    };
        toastr.success('Welcome <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>', 'Intership project');

        // Set the flag to indicate that the notification has been shown
        <?php $_SESSION['has_shown'] = 'true'; ?>
    }, 1300);
    }
    });


    const deleteButtons = document.querySelectorAll('.deleteBtn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = button.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this user!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'backend/delete.php?deleteid=' + userId;
                }
            });
        });
    });

    function previewImage(event) {
        var input = event.target;
        var reader = new FileReader();

        reader.onload = function() {
            var dataURL = reader.result;
            var profilePic = document.getElementById('profile-pic');
            profilePic.src = dataURL;
        };

        reader.readAsDataURL(input.files[0]);
    }
</script>
</body>
</html>
