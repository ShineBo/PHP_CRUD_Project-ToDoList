<?php
session_start();
include '../config/db.php';

function login($username, $password, $role) {
    $conn = connectDatabase();

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? AND role = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_password);
        $stmt->fetch();

        if ($password === $db_password) { 
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }

    $stmt->close();
    $conn->close();
}
?>