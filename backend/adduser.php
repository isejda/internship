<?php
session_start();
require_once(__DIR__ . '/../include/conn.php');
// insert record

global $conn;

if(isset($_POST['operation'])){
//    print_r($_POST['operation']);
//    exit;
    if($_POST['operation'] == "Save"){
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $birthday = $_POST['birthday'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
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
    }
    if($_POST['operation'] == "Edit"){
        if (isset($_POST['member_id'])) {
            $id = $_POST['member_id'];
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $birthday = $_POST['birthday'];
            $selectedRole = $_POST['role'];
            $password = $_POST['password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $select = "select * from users where id='$id'";

            $sql = mysqli_query($conn, $select);
            $row = mysqli_fetch_assoc($sql);
            $res = $row['id'];
            $oldEmail = $row['email'];

            $update = "UPDATE users 
                        SET name = '$name',
                            lastname = '$lastname',
                            birthday ='$birthday' ,
                            role ='$selectedRole' ";

            if(!empty($password)){
                $update .= ", password = '$hashed_password'";
            }
            $flag = '1';
            if($oldEmail == $email){
                $update .= ", email = '$email'";
            } else{
                $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
                $result = $conn->query($check_email_sql);

                if ($result->num_rows > 0) {
                    echo "<span class='error'>Email already exists!</span>";
                    $flag = '0';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<span class='error'>Invalid email format</span>";
                    $flag = '0';
                } else {
                    $update .= ", email = '$email'";
                }
            }
            $update .= " WHERE id = '$id'";


            $result = mysqli_query($conn, $update);
            if ($result && $flag == '1') {
                echo 'Your update was successful';
            }

        }

    }
}





