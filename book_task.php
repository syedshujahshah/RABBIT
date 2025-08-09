<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    header('Location: login.php');
    exit;
}

$task_id = $_GET['task_id'];
$stmt = $conn->prepare("SELECT ta.*, u.username FROM task_applications ta JOIN users u ON ta.tasker_id = u.id WHERE ta.task_id = ?");
$stmt->execute([$task_id]);
$applications = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tasker_id = $_POST['tasker_id'];
    $stmt = $conn->prepare("UPDATE task_applications SET status = 'accepted' WHERE task_id = ? AND tasker_id = ?");
    $stmt->execute([$task_id, $tasker_id]);

    $stmt = $conn->prepare("INSERT INTO bookings (task_id, tasker_id, client_id) VALUES (?, ?, ?)");
    $stmt->execute([$task_id, $tasker_id, $_SESSION['user_id']]);

    $stmt = $conn->prepare("SELECT budget FROM tasks WHERE id = ?");
    $stmt->execute([$task_id]);
    $task = $stmt->fetch();

    $stmt = $conn->prepare("INSERT INTO payments (booking_id, amount) VALUES ((SELECT MAX(id) FROM bookings), ?)");
    $stmt->execute([$task['budget']]);

    header('Location: tasks.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Task - TaskRabbit Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #2e7d32;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        nav a {
            color: white;
            margin: 0 1rem;
            text-decoration: none;
            font-weight: bold;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .application {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        button {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1b5e20;
        }
        @media (max-width: 768px) {
            .container {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>TaskRabbit Clone</h1>
        <nav>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('profile.php')">Profile</a>
            <a href="#" onclick="redirect('tasks.php')">Tasks</a>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>Book a Tasker</h2>
        <?php foreach ($applications as $application): ?>
            <div class="application">
                <p><strong>Tasker:</strong> <?php echo htmlspecialchars($application['username']); ?></p>
                <form method="POST">
                    <input type="hidden" name="tasker_id" value="<?php echo $application['tasker_id']; ?>">
                    <button type="submit">Book Tasker</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
