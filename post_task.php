<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $budget = $_POST['budget'];
    $location = $_POST['location'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, category, budget, location) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $category, $budget, $location]);

    header('Location: tasks.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Task - TaskRabbit Clone</title>
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
            max-width: 600px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2e7d32;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        input, textarea, select {
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
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </nav>
    </header>
    <div class="container">
        <div class="form-container">
            <h2>Post a Task</h2>
            <form method="POST">
                <input type="text" name="title" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description" required></textarea>
                <select name="category" required>
                    <option value="Cleaning">Cleaning</option>
                    <option value="Furniture Assembly">Furniture Assembly</option>
                    <option value="Handyman">Handyman</option>
                    <option value="Moving Help">Moving Help</option>
                </select>
                <input type="number" name="budget" placeholder="Budget ($)" required>
                <input type="text" name="location" placeholder="Location" required>
                <button type="submit">Post Task</button>
            </form>
        </div>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
