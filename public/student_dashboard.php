<?php
session_start();
include '../includes/functions.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Nunito', sans-serif;
        }
        .thead-dark {
            background-color: #343a40;
            color: white;
        }
        .container {
            background-color: #f1faff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Student Dashboard</h1>
        <a href="logout.php" class="btn btn-danger mb-4">Logout</a>
        <h2 class="mb-4">Student To-Do List</h2>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
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
    </div>
</body>
</html>