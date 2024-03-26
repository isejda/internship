<?php
session_start();

include "../include/conn.php";

if(isset($_POST['edit'])) {
    $page = $_POST['page'];
    if ($page === "updateprofile") {
        $id = $_SESSION['id'];
    } else if ($page === "updateusers") {
        $id = $_POST['id'];
    }
    /*    print_r($id);
        exit;*/


    $name = $conn->escape_string($_POST['name']);
    $lastname = $conn->escape_string($_POST['lastname']);
    $email = $conn->escape_string($_POST['email']);
    $password = $conn->escape_string($_POST['password']);
    $confirmPassword = $conn->escape_string($_POST['confirmPassword']);
    $birthday = $conn->escape_string($_POST['birthday']);
    $selectedRole = $_POST['role'];
    $alphanumericRegex = '/^[a-zA-Z]+$/';
    $dob = new DateTime($birthday);
    $now = new DateTime();
    $age = $now->diff($dob)->y;
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    $validationErrors = array();

    $select = "select * from users where id='$id'";

    $sql = mysqli_query($conn, $select);
    $row = mysqli_fetch_assoc($sql);
    $res = $row['id'];
    $oldEmail = $row['email'];
//    print_r($oldEmail);
//    print_r($email);
//    exit;

    /*    print_r($id);
        print_r($res);
        exit;*/
    if ($res == $id) {

        $update = "UPDATE users 
                    SET  ";

        if (preg_match($alphanumericRegex, $name)) {
            $update .= " name = '$name'";
        } else {
            $validationErrors['name'] = "Name must contain only letters";
        }

        if (preg_match($alphanumericRegex, $lastname)) {
            $update .= ", lastname = '$lastname'";
        } else {
            $validationErrors['lastname'] = "Last name must contain only letters";
        }

        if($oldEmail != $email){
            $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
            $result = $conn->query($check_email_sql);

            if ($result->num_rows > 0) {
                $validationErrors['email'] = "Email already exists!";
            } else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $update .= ", email = '$email'";
            } else {
                $validationErrors['email'] = "Invalid email format";
            }
        }


        $update .= ", role ='$selectedRole'";

        if ($age >= 18) {
            $update .= ", birthday ='$birthday'";
        } else {
            $validationErrors['birthday'] = "You must be at least 18 years old.";
        }

        if (!empty($password) && (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8)) {
            $validationErrors['password'] = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
        } else if (!empty($password) && $password == $confirmPassword &&
            ($uppercase && $lowercase && $number && $specialChars && strlen($password) > 8)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update .= ", password = '$hashed_password'";
        }

        if ($password != $confirmPassword) {
            $validationErrors['confirmPassword'] = "Password does not match";
        }
//
//        print_r($_FILES);
//        exit;
        if ($_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $picture_tmp_name = $_FILES['picture']['tmp_name'];
            $picture_name = $_FILES['picture']['name'];

            $upload_directory = "../storage/photos/";
            $destination = $upload_directory . $picture_name;
            $fileType = pathinfo($destination, PATHINFO_EXTENSION);
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($picture_tmp_name, $destination)) {
                    $update .= ", picture = '$destination'";
                } else {
                    $validationErrors['picture'] = "Failed to upload picture.";
                }
            } else {
                $validationErrors['picture'] = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
            }
        }


//        print_r($validationErrors);
//        exit;

        if (!empty($validationErrors)) {
            $_SESSION['profile_form_validations'] = $validationErrors;
            if ($page === "updateprofile") {
                header('Location: ../profile.php');
                exit;
            } else if ($page === "updateusers") {
                header("Location: ../update.php?id=$id");
                exit;
            }
        }

        $update .= " WHERE id = '$id'";

//        print_r($update);
//        exit;
        $sql2 = mysqli_query($conn, $update);
        if ($sql2) {
            if ($page === "updateprofile") {
                header('Location: ../profile.php');
                exit;
            } else if ($page === "updateusers") {
                header("Location: ../dashboard.php");
                exit;
            }

        } else {
            /*sorry your profile is not update*/
            header('location:../logout.php');
        }
    } else {
        /*sorry your id is not match*/
        header('location:../logout.php');
    }
}
?>