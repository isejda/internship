<?php
session_start();
require_once "../include/conn.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->escape_string($_POST['email']);
    $password = $conn->escape_string($_POST['password']);
    $alphanumericRegex = '/^[a-zA-Z]+$/';
    $validationErrors = array();

    if (empty($email)) {
        $validationErrors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationErrors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $validationErrors['password'] = "Password is required";
    }

    if (!empty($validationErrors)) {
        $_SESSION['login_form_validations'] = [
            "errors" => $validationErrors,
            "data" => $_POST,
        ];
        header('Location: ../login.php');
        exit;
    }

    /**
     * Validimi i E-Mailit. Shohim nese E-Maili egziston ne platforme
     */
    $query_check = "SELECT *
                    FROM users 
                    WHERE email = '$email'";

    $result_check = mysqli_query($conn, $query_check);


    if (!$result_check) {
        $validationErrors['email'] = "Internal Server Error";
        $_SESSION['login_form_validations'] = [
            "errors" => $validationErrors,
            "data" => $_POST,
        ];
        header('Location: ../login.php');
        exit;

    }

    if (mysqli_num_rows($result_check) == 0) {
        $validationErrors['email'] = "User with this email does not exists on system";
        $_SESSION['login_form_validations'] = [
            "errors" => $validationErrors,
            "data" => $_POST,
        ];
        header('Location: ../login.php');
        exit;
    }

    if (mysqli_num_rows($result_check) == 1) {
        $row_data = mysqli_fetch_assoc($result_check);
        if (password_verify($password, $row_data['password'])) {
            $_SESSION['id'] = $row_data['id'];
            $_SESSION["email"] = $email;

            header('Location: ../profile.php');
            exit;
        } else {
            $validationErrors['password'] = "Incorrect password!";
            $_SESSION['login_form_validations'] = [
                "errors" => $validationErrors,
                "data" => $_POST,
            ];
            header('Location: ../login.php');
            exit;
        }
    }
}
