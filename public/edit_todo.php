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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $resource_link = $_POST['resource_link'];
    $resource_link_name = $_POST['resource_link_name'];
    $class_day = $_POST['class_day'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("UPDATE student_todo_list SET title = ?, description = ?, resource_link = ?, resource_link_name = ?, class_day = ?, date = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $resource_link, $resource_link_name, $class_day, $date, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: teacher_dashboard.php");
    exit();
} else {
    $stmt = $conn->prepare("SELECT title, description, resource_link, resource_link_name, class_day, date FROM student_todo_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($title, $description, $resource_link, $resource_link_name, $class_day, $date);
    $stmt->fetch();
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit To-Do</title>
</head>
<body>
    <h1>Edit To-Do</h1>
    <form action="edit_todo.php?id=<?php echo $id; ?>" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea><br>

        <label for="resource_link">Resource Link:</label>
        <input type="url" id="resource_link" name="resource_link" value="<?php echo htmlspecialchars($resource_link); ?>" required><br>

        <label for="resource_link_name">Resource Link Name:</label>
        <input type="text" id="resource_link_name" name="resource_link_name" value="<?php echo htmlspecialchars($resource_link_name); ?>" required><br>

        <label for="class_day">Class Day:</label>
        <input type="text" id="class_day" name="class_day" value="<?php echo htmlspecialchars($class_day); ?>" required><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required><br>

        <button type="submit">Update</button>
    </form>
    <br>
    <a href="teacher_dashboard.php">Back to Dashboard</a>
</body>
</html>
