<?php
session_start();
include "include/validation.php";
?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Home</title>

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
            <div class="col-sm-4">
                <h2>Home</h2>
                <ol class="breadcrumb">
                    <li class="active">
                        <a href="#">Welcome</a>
                    </li>
                    <li class="active">
                        <strong>Home</strong>
                    </li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content">
                    <div class="middle-box text-center animated fadeInRightBig">
                        <h3 class="font-bold">This is page content</h3>
                        <div class="error-desc">
                            content here
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
