<?php
session_start();
include '../includes/functions.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $resource_link = $_POST['resource_link'];
    $resource_link_name = $_POST['resource_link_name'];
    $class_day = $_POST['class_day'];
    $date = $_POST['date'];

    $conn = connectDatabase();
    $stmt = $conn->prepare("INSERT INTO student_todo_list (title, description, resource_link, resource_link_name, class_day, date, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssssss", $title, $description, $resource_link, $resource_link_name, $class_day, $date);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: teacher_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create To-Do</title>
</head>
<body>
    <h1>Create New To-Do</h1>
    <form action="create_todo.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="resource_link">Resource Link:</label>
        <input type="url" id="resource_link" name="resource_link" required><br>

        <label for="resource_link_name">Resource Link Name:</label>
        <input type="text" id="resource_link_name" name="resource_link_name" required><br>

        <label for="class_day">Class Day:</label>
        <input type="text" id="class_day" name="class_day" required><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br>

        <button type="submit">Create</button>
    </form>
    <br>
    <a href="teacher_dashboard.php">Back to Dashboard</a>
</body>
</html>
