<?php
include "conn.php";
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query returned any rows
    if ($result->num_rows > 0) {
        // Fetch user data from the result set
        $userData = $result->fetch_assoc();

        // Store user data in session variables
        $_SESSION['id'] = $userData['id'];
        $_SESSION['name'] = $userData['name'];
        $_SESSION['lastname'] = $userData['lastname'];
        $_SESSION['picture'] = $userData['picture'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];
    } else {
        header("Location: ../404.html");
        exit();
    }
} else {

    header("Location: ../login.php");
    exit();
}


?>
