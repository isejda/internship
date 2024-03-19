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

    <title>INSPINIA | Dashboard</title>

    <?php
    include "include/header.php";
    ?>


</head>

<body>
<?php
include "include/menu.php";
?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>This is main title</h2>
        <ol class="breadcrumb">
            <li>
                <a href="home.php">This is</a>
            </li>
            <li class="active">
                <strong>Breadcrumb</strong>
            </li>
        </ol>
    </div>
    <div class="col-sm-8">
        <div class="title-action">
            <a href="" class="btn btn-primary">This is action area</a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content">
    <div class="middle-box text-center animated fadeInRightBig">
        <h3 class="font-bold">This is page content</h3>
        <div class="error-desc">
            You can create here any grid layout you want. And any variation layout you imagine:) Check out
            main dashboard and other site. It use many different layout.
            <br/><a href="home.php" class="btn btn-primary m-t">Dashboard</a>
        </div>
    </div>
</div>
<?php
include "include/footer.php";
include "include/scripts.php";
?>

</body>

</html>
