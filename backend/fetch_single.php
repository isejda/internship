<?php
require_once(__DIR__ . '/../include/conn.php');
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
        echo json_encode($output);
    } else {
        echo json_encode(array("error" => "Member not found"));
    }
}
