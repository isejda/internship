<?php
session_start();
require_once(__DIR__ . '/../include/conn.php');
// insert record

    global $conn;
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';
    $picture = '../storage/photos/download.jpeg';
    $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        echo "<span class='error'>Email already exists!</span>";
    }
    else{
        $query = "INSERT INTO users (name, lastname, email, birthday, password, role, picture)
          VALUES ('$name', '$lastname', '$email', '$birthday', '$hashed_password', '$role', '$picture')";

        $result = mysqli_query($conn, $query);
        if($result){
            echo 'Your record has been saved';
        }
        else{
            echo "Your record has not been saved";
        }
    }





