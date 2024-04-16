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
/*        $name = $_POST['name'] ?? null;
        $boolan = true;
        $name = $name ?: 'No Name';
        $name = $name || 'No Name';
        $number = 2;
        if ($number === true) {

        }
        echo $boolan ? 'Yes' : 'No';*/
        $username = $_POST['username'];
        $sql="WITH difference_in_seconds AS (
                    SELECT
                    id,
                    username,
                    data_hyrje,
                    ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
            FROM hyrje_dalje_kryesore
        ),

        summed_differences AS (
            SELECT
                username,
                YEAR(data_hyrje) AS year,
                SUM(seconds) AS total_seconds
            FROM difference_in_seconds
            GROUP BY username, YEAR(data_hyrje)
        )

        SELECT
            year,
            FLOOR(SUM(total_seconds) / 3600) as hours,
            FLOOR((SUM(total_seconds) % 3600) / 60) as minutes,
            SUM(total_seconds) % 60 as seconds
        FROM summed_differences
        WHERE username = '$username'
        GROUP BY year;";
/*
        $sql = "SELECT DISTINCT YEAR(hdk.data_hyrje) AS work_year
                FROM hyrje_dalje_kryesore hdk
                JOIN users u ON hdk.username = u.user_username
                WHERE u.user_username = '$username'";*/
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
/*            echo "<pre>";
            print_r($data);
            echo "</pre>";*/
            echo json_encode($data);
        } else {
            echo json_encode(array());
        }

/*        if ($result) {
            $years = array();
            // marrim cdo rresht nga result
            while ($row = mysqli_fetch_assoc($result)) {
                // shtojme vitin ne array
                $years[] = $row['work_year'];
            }

            // dergo te dhenat
            echo json_encode($years);
        } else {
            // dergo nje array bosh ne rast problemi
            echo json_encode(array());
        }*/

    }
    if($_POST['operation'] == "Months") {
        $username = $_POST['username'];
        $year = $_POST['year'];

        $sql="WITH difference_in_seconds AS (
        SELECT
            id,
            username,
            data_hyrje,
            ora_hyrje,
            CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
            CASE
                WHEN ora_dalje = '00:00:00' THEN
                    TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                    )
                ELSE
                    TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                    )
            END AS seconds
        FROM hyrje_dalje_kryesore
    ),
    summed_differences AS (
        SELECT
            username,
            YEAR(data_hyrje) AS year,
            MONTH(data_hyrje) AS month,
            SUM(seconds) AS total_seconds
        FROM difference_in_seconds
        WHERE username = '$username' AND YEAR(data_hyrje) = '$year'
        GROUP BY username, YEAR(data_hyrje), MONTH(data_hyrje)
    )

SELECT
    year,
    month,
    FLOOR(SUM(total_seconds) / 3600) AS hours,
    FLOOR((SUM(total_seconds) % 3600) / 60) AS minutes,
    SUM(total_seconds) % 60 AS seconds
FROM summed_differences
GROUP BY year, month;";


        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            /*            echo "<pre>";
                        print_r($data);
                        echo "</pre>";*/
            echo json_encode($data);
        } else {
            echo json_encode(array());
        }

    }
    if($_POST['operation'] == "Days") {

        $username = $_POST['username'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $nextMonth = $month +1;

        $sql="WITH difference_in_seconds AS (
                SELECT
                id,
                username,
                data_hyrje,
                ora_hyrje,
                CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
                CASE
                WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
                ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
                END AS seconds
                FROM hyrje_dalje_kryesore
                ),

                summed_differences AS (
                    SELECT
                            username,
                            DATE(data_hyrje) AS date_hyrje,
                            SUM(seconds) AS total_seconds
                    FROM difference_in_seconds
                     WHERE username = '$username'
                        AND YEAR(data_hyrje) = '$year'
                        AND MONTH(data_hyrje) >= '$month'
                        AND MONTH(data_hyrje) < '$nextMonth'
                     GROUP BY username, date_hyrje
                )

            SELECT
                date_hyrje,
                FLOOR(SUM(total_seconds) / 3600) as hours,
                FLOOR((SUM(total_seconds) % 3600) / 60) as minutes,
                SUM(total_seconds) % 60 as seconds
            FROM summed_differences
            GROUP BY date_hyrje;";


        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            /*            echo "<pre>";
                        print_r($data);
                        echo "</pre>";*/
            echo json_encode($data);
        } else {
            echo json_encode(array());
        }

    }

    if($_POST['operation'] == "Hours") {

        $username = $_POST['username'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $nextMonth = $month +1;
        $day = $_POST['day'];


        $sql="WITH difference_in_seconds AS (
    SELECT
        id,
        username,
        data_hyrje,
        ora_hyrje,
        CASE WHEN ora_dalje = '00:00:00' THEN '24:00:00' ELSE ora_dalje END AS ora_dalje_adjusted,
        CASE
            WHEN ora_dalje = '00:00:00' THEN
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        TIMESTAMPADD(DAY, 1, ora_dalje)
                )
            ELSE
                TIMESTAMPDIFF(
                        SECOND,
                        ora_hyrje,
                        ora_dalje
                )
            END AS seconds
    FROM hyrje_dalje_kryesore
),

     differences AS (
         SELECT
             id,
             username,
             data_hyrje,
             ora_hyrje,
             ora_dalje_adjusted AS ora_dalje,
             seconds,
             MOD(seconds, 60) AS seconds_part,
             MOD(seconds, 3600) AS minutes_part,
             MOD(seconds, 3600 * 24) AS hours_part
         FROM difference_in_seconds
     )

SELECT
    id,
    data_hyrje,
    ora_hyrje,
    ora_dalje,
    CONCAT(
            FLOOR(hours_part / 3600), ' hours ',
            FLOOR(minutes_part / 60), ' minutes ',
            seconds_part, ' seconds'
    ) AS difference
FROM differences
WHERE
    YEAR(data_hyrje) = '$year'
  AND MONTH(data_hyrje) = '$month'
  AND DAY(data_hyrje) = '$day'
  AND username = '$username';";


        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            /*            echo "<pre>";
                        print_r($data);
                        echo "</pre>";*/
            echo json_encode($data);
        } else {
            echo json_encode(array());
        }

    }

}





