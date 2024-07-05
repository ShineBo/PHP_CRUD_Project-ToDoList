<?php
session_start();
include '../includes/functions.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = connectDatabase();

// Fetch all to-do items for the logged-in student
$sql = "SELECT stl.id, stl.title, stl.description, stl.resource_link, stl.resource_link_name, stl.class_day, stl.date 
        FROM student_todo_list stl";
$result = $conn->query($sql);

$todo_items = [];
while ($row = $result->fetch_assoc()) {
    $todo_items[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Student Dashboard</h1>
    <a href="logout.php">Logout</a>
    <h2>Student-To-Do List</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Resource Link</th>
                <th>Class Day</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($todo_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($item['resource_link']); ?>"><?php echo htmlspecialchars($item['resource_link_name']); ?></a></td>
                    <td><?php echo htmlspecialchars($item['class_day']); ?></td>
                    <td><?php echo htmlspecialchars($item['date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>