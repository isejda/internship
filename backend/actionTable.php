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
            5 => 'role'
        );

        if ($userRole == 'admin') {
            // Fetch data for admin (all users)
            $sql = "SELECT * FROM users WHERE role = 'user' || role = 'manager'";
        }elseif ($userRole == 'user' || $userRole == 'manager') {
            // Fetch data for manager (users reporting to the manager)
            $sql = "WITH RECURSIVE SupervisedUsers AS (
                SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
                FROM users u
                JOIN hierarchy h ON u.id = h.subordinate_id
                WHERE h.supervisor_id = (SELECT id FROM users WHERE id = $userId)

                UNION ALL

                SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
                FROM users u
                JOIN hierarchy h ON u.id = h.subordinate_id
                JOIN SupervisedUsers su ON h.supervisor_id = su.id
            )
            SELECT * FROM SupervisedUsers;";
        }

        $query = mysqli_query($conn, $sql);
        $totalData = mysqli_num_rows($query);
        $totalFilter = $totalData;

/*        if ($userRole == 'admin') {
            // Fetch data for admin (all users)
            $sql = "SELECT * FROM users WHERE role = 'user'";
        }elseif ($userRole == 'user' || $userRole == 'manager') {
            // Fetch data for manager (users reporting to the manager)
            $sql = "WITH RECURSIVE SupervisedUsers AS (
                SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
                FROM users u
                JOIN hierarchy h ON u.id = h.subordinate_id
                WHERE h.supervisor_id = (SELECT id FROM users WHERE id = $userId)

                UNION ALL

                SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
                FROM users u
                JOIN hierarchy h ON u.id = h.subordinate_id
                JOIN SupervisedUsers su ON h.supervisor_id = su.id
            )
            SELECT * FROM SupervisedUsers;";
        }

        //searchi funksional
        if (!empty($request['search']['value'])) {
            $sql .= " AND (id Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR lastname Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR email Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR birthday Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR role Like '%" . $request['search']['value'] . "%' ";
            $sql .= " OR name Like '%" . $request['search']['value'] . "%' )";

        }*/

        if ($userRole == 'admin') {
            // Fetch data for admin (all users)
            $sql = "SELECT * FROM users WHERE role = 'user' || role = 'manager'";
            if (!empty($request['search']['value'])) {
                $sql .= " AND (id Like '%" . $request['search']['value'] . "%' ";
                $sql .= " OR lastname Like '%" . $request['search']['value'] . "%' ";
                $sql .= " OR email Like '%" . $request['search']['value'] . "%' ";
                $sql .= " OR birthday Like '%" . $request['search']['value'] . "%' ";
                $sql .= " OR role Like '%" . $request['search']['value'] . "%' ";
                $sql .= " OR name Like '%" . $request['search']['value'] . "%' )";
            }
        }elseif ($totalFilter == 0){
            $sql = "SELECT * FROM users WHERE id = $userId";
        } elseif ($userRole == 'user' || $userRole == 'manager') {
            // Fetch data for manager (users reporting to the manager)
            $recursiveSql = "
        WITH RECURSIVE SupervisedUsers AS (
            SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
            FROM users u
            JOIN hierarchy h ON u.id = h.subordinate_id
            WHERE h.supervisor_id = (SELECT id FROM users WHERE id = $userId)
            
            UNION ALL
            
            SELECT u.id, u.name, u.lastname, u.email, u.birthday, u.role
            FROM users u
            JOIN hierarchy h ON u.id = h.subordinate_id
            JOIN SupervisedUsers su ON h.supervisor_id = su.id
        )
        SELECT * FROM SupervisedUsers";

            $sql = "SELECT * FROM ($recursiveSql) AS subquery";

            if (!empty($request['search']['value'])) {
                $searchValue = mysqli_real_escape_string($conn, $request['search']['value']);
                $searchConditions = [
                    "id LIKE '%$searchValue%'",
                    "lastname LIKE '%$searchValue%'",
                    "email LIKE '%$searchValue%'",
                    "birthday LIKE '%$searchValue%'",
                    "role LIKE '%$searchValue%'",
                    "name LIKE '%$searchValue%'"
                ];

                // Add WHERE clause only if there are search conditions
                $sql .= " WHERE " . implode(" OR ", $searchConditions);
            }
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
        // Variable to track whether the logged-in user data has been added to the table
        $loggedInUserAdded = false;

        if ($userRole == 'admin') {
            // Fetch data for admin (all users)
            while ($row = mysqli_fetch_array($query)) {
                $subdata = array();
                $subdata[] = '';
                $subdata[] = $row[0]; //id
                $subdata[] = $row[1]; //name
                $subdata[] = $row[2]; //lastname
                $subdata[] = $row[3]; //email
                $subdata[] = $row[4]; //birthday
                $subdata[] = $row[5]; //role
                $subdata[] = '
                            <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li>
                                <button type="button" name="update" id="' . $row[0] . '" class="btn btn-w-m btn-xm update-user-btn"><i class="fa fa-edit">&nbsp;</i>Edit</button>
                                </li>
                                <li class="divider"></li>
                                <li>
                                <button type="button" name="delete" id="' . $row[0] . '" class="btn btn-w-m btn-xm delete-user-btn" ><i class="fa fa-trash">&nbsp;</i>Delete</button>
                                </li>
                                
                            </ul>
                            </div>
                
               ';
                /*                  <a href="backend/delete.php?deleteid='.$row[0].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-w-m btn-danger btn-xm"><i class="fa fa-trash">&nbsp;</i>Delete</a>';*/
                    $data[] = $subdata;
            }
        }elseif ($userRole == 'user' || $userRole == 'manager') {
            // Fetch data for manager (users reporting to the manager)

            while ($row = mysqli_fetch_array($query)) {
                $subdata = array();
                $subdata[] = $row[0]; //id
                $subdata[] = $row[1]; //name
                $subdata[] = $row[2]; //lastname
                $subdata[] = $row[3]; //email
                $subdata[] = $row[4]; //birthday
                $subdata[] = $row[5]; //role
                $data[] = $subdata;
            }

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
}





