<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT t.*, u.username FROM tasks t JOIN users u ON t.user_id = u.id WHERE t.status = 'pending'";
if ($category) {
    $query .= " AND t.category = :category";
}
if ($search) {
    $query .= " AND t.title LIKE :search";
}

$stmt = $conn->prepare($query);
if ($category) {
    $stmt->bindValue(':category', $category);
}
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->execute();
$tasks = $stmt->fetchAll();

if ($_SESSION['user_type'] == 'tasker' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $stmt = $conn->prepare("INSERT INTO task_applications (task_id, tasker_id) VALUES (?, ?)");
    $stmt->execute([$task_id, $user_id]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - TaskRabbit Clone</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .search-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        input, select {
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
        .task-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .task {
            flex: 1 1 300px;
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            .task-list {
                flex-direction: column;
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
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>Available Tasks</h2>
        <div class="search-bar">
            <input type="text" id="search" placeholder="Search tasks..." value="<?php echo htmlspecialchars($search); ?>">
            <select id="category">
                <option value="">All Categories</option>
                <option value="Cleaning" <?php echo $category == 'Cleaning' ? 'selected' : ''; ?>>Cleaning</option>
                <option value="Furniture Assembly" <?php echo $category == 'Furniture Assembly' ? 'selected' : ''; ?>>Furniture Assembly</option>
                <option value="Handyman" <?php echo $category == 'Handyman' ? 'selected' : ''; ?>>Handyman</option>
                <option value="Moving Help" <?php echo $category == 'Moving Help' ? 'selected' : ''; ?>>Moving Help</option>
            </select>
            <button onclick="searchTasks()">Search</button>
        </div>
        <div class="task-list">
            <?php foreach ($tasks as $task): ?>
                <div class="task">
                    <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                    <p><strong>Posted by:</strong> <?php echo htmlspecialchars($task['username']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($task['category']); ?></p>
                    <p><strong>Budget:</strong> $<?php echo htmlspecialchars($task['budget']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($task['location']); ?></p>
                    <p><?php echo htmlspecialchars($task['description']); ?></p>
                    <?php if ($_SESSION['user_type'] == 'tasker'): ?>
                        <form method="POST">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit">Apply for Task</button>
                        </form>
                    <?php endif; ?>
                    <?php if ($_SESSION['user_type'] == 'client' && $task['user_id'] == $user_id): ?>
                        <button onclick="redirect('messages.php?task_id=<?php echo $task['id']; ?>')">View Messages</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
        function searchTasks() {
            const search = document.getElementById('search').value;
            const category = document.getElementById('category').value;
            let url = 'tasks.php';
            if (search || category) {
                url += '?';
                if (search) url += 'search=' + encodeURIComponent(search);
                if (category) url += (search ? '&' : '') + 'category=' + encodeURIComponent(category);
            }
            redirect(url);
        }
    </script>
</body>
</html>
