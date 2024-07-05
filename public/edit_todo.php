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

    $_SESSION['message'] = "To-do item updated successfully!";
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Nunito', sans-serif;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f1faff;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #343a40;
        }
        .form-container .form-group {
            margin-bottom: 20px;
        }
        .form-container .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        .form-container .btn {
            width: 100%;
            background-color: #0d1f2e;
            border-color: #0d1f2e;
            color: #ffffff;
            border-radius: 20px;
            padding: 10px 0;
        }
        .form-container .btn:hover {
            background-color: #384149;
            border-color: #384149;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Edit To-Do</h2>
            <form action="edit_todo.php?id=<?php echo $id; ?>" method="post">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="resource_link">Resource Link:</label>
                    <input type="url" class="form-control" id="resource_link" name="resource_link" value="<?php echo htmlspecialchars($resource_link); ?>" required>
                </div>
                <div class="form-group">
                    <label for="resource_link_name">Resource Link Name:</label>
                    <input type="text" class="form-control" id="resource_link_name" name="resource_link_name" value="<?php echo htmlspecialchars($resource_link_name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="class_day">Class Day:</label>
                    <input type="text" class="form-control" id="class_day" name="class_day" value="<?php echo htmlspecialchars($class_day); ?>" required>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
                </div>
                <button type="submit" class="btn btn-secondary btn-sm">Update</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>