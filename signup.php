<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_type, full_name, phone, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $user_type, $full_name, $phone, $location]);

    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - TaskRabbit Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .signup-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #2e7d32;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
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
        a {
            text-align: center;
            color: #2e7d32;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .signup-container {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="user_type" required>
                <option value="client">Client</option>
                <option value="tasker">Tasker</option>
            </select>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="#" onclick="redirect('login.php')">Login</a></p>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
