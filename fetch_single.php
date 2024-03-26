<?php
include('include/conn.php');
function get_total_all_records()
{
    $statement = $conn->prepare("SELECT * FROM users");
    $statement->execute();
    $result = $statement->fetchAll();
    return $statement->rowCount();
}

if(isset($_POST["member_id"]))
{
    $output = array();
    $statement = $conn->prepare( "SELECT * FROM users WHERE id = ? LIMIT 1");
    $statement->bind_param("i", $_POST["member_id"]);
    $statement->execute();
    $result = $statement->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $output["id"] = $row["id"];
        $output["name"] = $row["name"];
        $output["lastname"] = $row["lastname"];
        $output["email"] = $row["email"];
        $output["birthday"] = $row["birthday"];
        $output["password"] = $row["password"];
        echo json_encode($output);
    } else {
        // Handle case where no member with the given ID is found
        echo json_encode(array("error" => "Member not found"));
    }
}
?>
