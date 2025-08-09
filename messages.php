<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['task_id'])) {
    header('Location: login.php');
    exit;
}

$task_id = $_GET['task_id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $receiver_id = $_POST['receiver_id'];

    $stmt = $conn->prepare("INSERT INTO messages (task_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$task_id, $user_id, $receiver_id, $message]);
}

$stmt = $conn->prepare("SELECT m.*, u.username FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.task_id = ? ORDER BY m.sent_at");
$stmt->execute([$task_id]);
$messages = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT t.user_id FROM tasks t WHERE t.id = ?");
$stmt->execute([$task_id]);
$task = $stmt->fetch();
$client_id = $task['user_id'];

$stmt = $conn->prepare("SELECT u.id, u.username FROM task_applications ta JOIN users u ON ta.tasker_id = u.id WHERE ta.task_id = ?");
$stmt->execute([$task_id]);
$taskers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - TaskRabbit Clone</title>
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
        .message-box {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        textarea, select {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
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
        <h2>Messages for Task</h2>
        <?php foreach ($messages as $message): ?>
            <div class="message-box">
                <p><strong><?php echo htmlspecialchars($message['username']); ?>:</strong> <?php echo htmlspecialchars($message['message']); ?></p>
                <p><small><?php echo $message['sent_at']; ?></small></p>
            </div>
        <?php endforeach; ?>
        <form method="POST">
            <select name="receiver_id" required>
                <?php if ($_SESSION['user_type'] == 'client'): ?>
                    <?php foreach ($taskers as $tasker): ?>
                        <option value="<?php echo $tasker['id']; ?>"><?php echo htmlspecialchars($tasker['username']); ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="<?php echo $client_id; ?>">Client</option>
                <?php endif; ?>
            </select>
            <textarea name="message" placeholder="Type your message..." required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
