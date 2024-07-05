<?php
session_start();
include '../includes/functions.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Fetch all to-do items
$conn = connectDatabase();
$sql = "SELECT id, title, description, resource_link, resource_link_name, class_day, date, status FROM student_todo_list";
$result = $conn->query($sql);

$todo_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $todo_items[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
</head>
<body>
    <h1>Teacher Dashboard</h1>
    <a href="logout.php">Logout</a>
    <h2>Student-To-Do List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Resource Link</th>
                <th>Class Day</th>
                <th>Date</th>
                <!-- <th>Status</th> -->
                <th>Actions</th>
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
                    <!-- <td><?php echo htmlspecialchars($item['status']); ?></td> -->
                    <td>
                        <a href="edit_todo.php?id=<?php echo $item['id']; ?>">Edit</a>
                        <a href="delete_todo.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="create_todo.php">Create New To-Do</a>
</body>
</html>

