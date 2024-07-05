<?php
function connectDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Student_To_Do_List";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
