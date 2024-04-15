<?php
session_start();
require_once(__DIR__ . '/../include/conn.php');
global $conn;

if(isset($_POST['operation'])){
    if($_POST['operation'] == "Insert") {
//In other words, $_REQUEST is an array containing data from $_GET, $_POST, and $_COOKIE.

        $request = $_REQUEST;
        $userRole = $_SESSION['role'];
        $userId = $_SESSION['id'];

        $sql= "";
//create col like table in database
        $col = array(
            0 => 'id',
            1 => 'name',
            2 => 'lastname',
            3 => 'email',
            4 => 'birthday',
            5 => 'role',
            6 => 'user_username'
        );
        $sql = "SELECT id as 'id', name as 'name', lastname as 'lastname', email as 'email', birthday as 'birthday', role as 'role', user_username as 'username' FROM users WHERE role = 'user'";
        $query = mysqli_query($conn, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

        $sql = "SELECT id as 'id', name as 'name', lastname as 'lastname', email as 'email', birthday as 'birthday', role as 'role', user_username as 'username' FROM users WHERE role = 'user'";

        if (!empty($request['search']['value'])) {
            $sql .= " AND (id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR lastname Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR email Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR birthday Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR role Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR user_username Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR name Like '%" . $request['search']['value'] . "%' )";
        }

// Add search functionality
        $query = mysqli_query($conn, $sql);
        $totalData = mysqli_num_rows($query);

// order by id the third time
        $defaultOrderColumn = 'id';
        $defaultOrderDirection = 'ASC';

// nqs parametri order is set bejme order sipas radhes se caktuar
        if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
            $orderColumn = $col[$_REQUEST['order'][0]['column']];
            $orderDirection = $_REQUEST['order'][0]['dir'];
        } else { //ne te kundert bejme order sipas id
            $orderColumn = $defaultOrderColumn;
            $orderDirection = $defaultOrderDirection;
        }

//do order
        $sql .= " ORDER BY " . $orderColumn . " " . $orderDirection . " LIMIT " .
            intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);

        $query = mysqli_query($conn, $sql);

        $data = array();
        while ($row = mysqli_fetch_assoc($query)) {
            $subdata = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "lastname" => $row['lastname'],
                "email" => $row['email'],
                "birthday" => $row['birthday'],
                "role" => $row['role'],
                "username" => $row['username'], // Include username in the data array
                "action" => '
            <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li>
                        <button type="button" name="update" id="' . $row['id'] . '" class="btn btn-w-m btn-xm update-user-btn"><i class="fa fa-edit">&nbsp;</i>Edit</button>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <button type="button" name="delete" id="' . $row['id'] . '" class="btn btn-w-m btn-xm delete-user-btn" ><i class="fa fa-trash">&nbsp;</i>Delete</button>
                    </li>
                </ul>
            </div>
        '
            );
            $data[] = $subdata;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFilter),
            "data" => $data
        );

        echo json_encode($json_data);


    }

    if($_POST['operation'] == "Save"){
        $supervisor_id = $_SESSION['id'];
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
                $subordinate_id = mysqli_insert_id($conn);
                $hierarchy_query = "INSERT INTO hierarchy (supervisor_id, subordinate_id)
                                VALUES ('$supervisor_id', '$subordinate_id')";
                $hierarchy_result = mysqli_query($conn, $hierarchy_query);

                if($hierarchy_result){
                    echo 'Your record has been saved. ';
                } else {
                    echo "Failed to save hierarchy information.";
                }
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
/*            print_r($selectedRole);
            exit;*/
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

    if($_POST['operation'] == "Delete") {
        if (isset($_POST['member_id'])) {
            $id = $_POST['member_id'];
            $query = "DELETE FROM hierarchy 
            WHERE subordinate_id = $id OR supervisor_id = $id";

            $res = $conn->query($query);

            if($res){
                $sql = " DELETE FROM users
            WHERE id = $id";

                $result = $conn->query($sql);
                if($result){
                    echo "Delete is successful";
                }
                else{
                    die($conn->error);
                }
            }
            else{
                die($conn->error);
            }

        }
    }

    if($_POST['operation'] == "years") {
        $username = $_POST['username'];

        $sql = "SELECT DISTINCT YEAR(hdk.data_hyrje) AS work_year
                FROM hyrje_dalje_kryesore hdk
                JOIN users u ON hdk.username = u.user_username
                WHERE u.user_username = '$username'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Initialize an array to store the years
            $years = array();

            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {
                // Add the year to the array
                $years[] = $row['work_year'];
            }

            // Send the years data as JSON
            echo json_encode($years);
        } else {
            // If there are no results or an error occurred, send an empty array
            echo json_encode(array());
        }
    }

}





