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

        // Debug information
        if ($password === $db_password) {
            echo "Passwords match. Logging in...";
        } else {
            echo "Passwords do not match.";
        }

        if ($password === $db_password) { 
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            return true;
        } else {
            return false;
        }
    } else {
        echo "No user found with that username and role.";
        return false;
    }

    $stmt->close();
    $conn->close();
}

function fetchAllUsers() {
    $conn = connectDatabase();
    $sql = "SELECT id, username, role FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }

    $conn->close();
}
?>