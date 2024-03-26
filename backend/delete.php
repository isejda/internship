<?php
global $conn;
include '../include/conn.php';
//access the parameter from the url

if(isset($_GET['deleteid'])){
/*    print_r($_GET['deleteid']);
    exit;*/
    $id = $_GET['deleteid'];
    $sql = " DELETE FROM users
            WHERE id = $id";

    $result = $conn->query($sql);
    if($result){
//        echo "deleted succesfully";
        header('location:../dashboard.php');
    }
    else{
        die($conn->error);
    }

}

?>
