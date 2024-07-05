<?php
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (login($username, $password, $role)) {
        if ($role == 'teacher') {
            header("Location: teacher_dashboard.php");
        } else if ($role == 'student') {
            header("Location: student_dashboard.php");
        }
    } else {
        echo "Invalid username, password, or role.";
    }
} else {
    include '../templates/login_form.html';
}
?>