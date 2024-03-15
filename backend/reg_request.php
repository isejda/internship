<?php
session_start();
require_once "../include/conn.php";
// Function to repopulate form fields
function repopulateField($fieldName) {
    return isset($_SESSION['register_form_data'][$fieldName]) ? $_SESSION['register_form_data'][$fieldName] : '';
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $page = $_POST['page'];
    $name = $conn->escape_string($_POST['name']);
    $lastname = $conn->escape_string($_POST['lastname']);
    $email = $conn->escape_string($_POST['email']);
    $password = $conn->escape_string($_POST['password']);
    $confirmPassword = $conn->escape_string($_POST['confirmPassword']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $birthday = $conn->escape_string($_POST['birthday']);
    $dob = new DateTime($birthday);
    $now = new DateTime();
    $age = $now->diff($dob)->y;
    $alphanumericRegex = '/^[a-zA-Z]+$/';
    $validationErrors = array();
    $check_value = isset($_POST['agree-term']);

    if (!$check_value) {
        $validationErrors['agree-term'] = "Please accept the agreement!";
    }

    if (empty($name)) {
        $validationErrors['name'] = "Name is required";
    } elseif (!preg_match($alphanumericRegex, $name)) {
        $validationErrors['name'] = "Name must contain only letters";
    }

    if (empty($lastname)) {
        $validationErrors['lastname'] = "Last name is required";
    } elseif (!preg_match($alphanumericRegex, $name)) {
        $validationErrors['lastname'] = "Last name must contain only letters";
    }


    if (empty($email)) {
        $validationErrors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationErrors['email'] = "Invalid email format";
    }


    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (empty($password)) {
        $validationErrors['password'] = "Password is required";
    } else if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $validationErrors['password'] = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
    }


    if (empty($confirmPassword)) {
        $validationErrors['confirmPassword'] = "Password is required";
    }

    if ($password != $confirmPassword) {
        $validationErrors['confirmPassword'] = "Password does not match";
    }

    if (empty($age)) {
        $validationErrors['birthday'] = "Birthday is required";
    } else if ($age < 18) {
        $validationErrors['birthday'] = "You must be at least 18 years old.";
    }

    /*    print_r($validationErrors);
        exit;*/

    if (!empty($validationErrors)) {
        $_SESSION['register_form_validations'] = $validationErrors;
        /*        if($page === "register"){
                    header('Location: register.php');
                }
                else if($page === "display"){
                    header('Location: display.php');
                }*/
        header('Location: ../register.php');

        exit;
    }


    $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        $validationErrors['email'] = "Email already exists!";
        $_SESSION['register_form_validations'] = $validationErrors;
        /*        if($page === "register"){
                    header('Location: register.php');
                }
                else if($page === "display"){
                    header('Location: display.php');
                }*/
        header('Location: ../register.php');

        exit;
    }

    $role = 'user';
    $sql = "INSERT INTO users (name, lastname, email, password, role,  birthday) 
        VALUES ('$name', '$lastname', '$email', '$hashed_password', '$role', ' $birthday')";


    if ($conn->query($sql)) {
        /*        if($page === "register"){
                    header('Location: login.php');
                }
                else if($page === "display"){
                    header('Location: display.php');
                }*/
        header('Location: ../login.php');

        exit;
    } else {
        $validationErrors['signup'] = "Problem tek te dhenat";
        $_SESSION['register_form_validations'] = $validationErrors;
        /*        if($page === "register"){
                    header('Location: register.php');
                }
                else if($page === "display"){
                    header('Location: display.php');
                }*/
        header('Location: ../register.php');

        exit;
    }
}

    if(isset($_SESSION['register_form_data'])) {
        extract($_SESSION['register_form_data']);
        unset($_SESSION['register_form_data']);
    }
