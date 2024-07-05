<?php
session_start();
include '../includes/functions.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$conn = connectDatabase();

$stmt = $conn->prepare("DELETE FROM student_todo_list WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION['message'] = "To-do item deleted successfully!";
header("Location: teacher_dashboard.php");
exit();
?>
