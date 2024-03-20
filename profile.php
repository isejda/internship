<?php
session_start();

include "include/validation.php";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>INSPINIA | Dashboard</title>

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
            <div class="col-lg-10">
                <h2>Profile</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="home.php">Home</a>
                    </li>
                    <li class="active">
                        <strong>Profile</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content">
                    <div class="row animated fadeInRight">
                        <div class="col-md-4">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Profile Detail</h5>
                                </div>
                                <div>
                                    <div class="ibox-content no-padding border-left-right">
                                        <img alt="image" class="img-responsive" src="inspina/<?=$data['picture']?>" style="width: 300px; height: 300px;" >
                                    </div>
                                    <div class="ibox-content profile-content">
                                        <h4><strong><?= $data['name'] . ' ' . $data['lastname']?></strong></h4>
                                        <p><i class="fa fa-envelope"></i><?= " " . $data['email']?> </p>

                                        <h5>
                                            Update your profile
                                        </h5>
                                        <a href="updateprofile.php"> <i class="fa fa-edit" style="font-size:24px"></i></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="ibox float-e-margins">
                                <?php if ($data['role'] === 'admin'): ?>
                                    <div class="ibox-title">
                                        <h5>Activities</h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link">
                                                <i class="fa fa-chevron-up"></i>
                                            </a>
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                <i class="fa fa-wrench"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-user">
                                                <li><a href="#">Config option 1</a>
                                                </li>
                                                <li><a href="#">Config option 2</a>
                                                </li>
                                            </ul>
                                            <a class="close-link">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($data['role'] === 'user'): ?>
                                    <div class="ibox-title">
                                        <h5>Contact an Admin</h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link">
                                                <i class="fa fa-chevron-up"></i>
                                            </a>
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                <i class="fa fa-wrench"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-user">
                                                <li><a href="#">Config option 1</a>
                                                </li>
                                                <li><a href="#">Config option 2</a>
                                                </li>
                                            </ul>
                                            <a class="close-link">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>LastName</th>
                                                    <th>Email</th>
                                                    <th>Contact</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $sql = "SELECT * FROM users where role = 'admin'";
                                                $result = $conn->query($sql);
                                                if($result){
                                                    while ($row = mysqli_fetch_assoc($result)){
                                                        $name = $row['name'];
                                                        $lastname = $row['lastname'];
                                                        $email = $row['email'];
                                                        echo '<tr>
            <td>'.$name.'</td>
            <td>'.$lastname.'</td>
            <td>'.$email.'</td>
            <td>
            <button class="btn btn-primary"><a href="#" style="color: #FFFFFF">Contact</a></button>
        </td>
        </tr>
                ';
                                                    };
                                                }
                                                ?>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
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
</script>

</body>
</html>
