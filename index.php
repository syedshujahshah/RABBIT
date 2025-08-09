<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskRabbit Clone - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
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
        .hero {
            background: linear-gradient(135deg, #43a047, #1b5e20);
            color: white;
            padding: 3rem;
            text-align: center;
            border-radius: 10px;
        }
        .categories, .taskers {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
        }
        .category, .tasker {
            flex: 1 1 300px;
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        button {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1b5e20;
        }
        @media (max-width: 768px) {
            .categories, .taskers {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>TaskRabbit Clone</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#" onclick="redirect('profile.php')">Profile</a>
                <a href="#" onclick="redirect('post_task.php')">Post Task</a>
                <a href="#" onclick="redirect('logout.php')">Logout</a>
            <?php else: ?>
                <a href="#" onclick="redirect('login.php')">Login</a>
                <a href="#" onclick="redirect('signup.php')">Signup</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">
        <div class="hero">
            <h2>Hire Local Experts for Any Task</h2>
            <p>From cleaning to moving, find trusted taskers in your area.</p>
            <button onclick="redirect('post_task.php')">Get Started</button>
        </div>
        <h2>Popular Services</h2>
        <div class="categories">
            <div class="category">
                <h3>Cleaning</h3>
                <p>Professional cleaning services for your home.</p>
            </div>
            <div class="category">
                <h3>Furniture Assembly</h3>
                <p>Expert help with assembling furniture.</p>
            </div>
            <div class="category">
                <h3>Handyman</h3>
                <p>Fixes and repairs by skilled professionals.</p>
            </div>
            <div class="category">
                <h3>Moving Help</h3>
                <p>Reliable movers for a stress-free move.</p>
            </div>
        </div>
        <h2>Top Taskers</h2>
        <div class="taskers">
            <div class="tasker">
                <h3>John Doe</h3>
                <p>Handyman - 4.8/5 (120 reviews)</p>
            </div>
            <div class="tasker">
                <h3>Jane Smith</h3>
                <p>Cleaning - 4.9/5 (150 reviews)</p>
            </div>
        </div>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
