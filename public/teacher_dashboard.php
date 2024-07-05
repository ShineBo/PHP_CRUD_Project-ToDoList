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

// Check for a message in the session and then clear it
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
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
        <h1 class="mb-4">Teacher Dashboard</h1>
        <a href="logout.php" class="btn btn-danger mb-4">Logout</a>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h2 class="mb-4">Student To-Do List</h2>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Resource Link</th>
                    <th>Class Day</th>
                    <th>Date</th>
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
                        <td>
                            <a href="edit_todo.php?id=<?php echo $item['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_todo.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="create_todo.php" class="btn btn-success">Create New To-Do</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000); // 5 seconds
            }
        });
    </script>
</body>
</html>