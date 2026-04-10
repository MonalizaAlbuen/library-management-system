<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config/db_config.php';

$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);

    if ($title && $author && $genre) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, genre) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $title, $author, $genre);

            if ($stmt->execute()) {
                $msg = "✅ Book added successfully!";
            } else {
                $msg = "❌ Error executing statement: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $msg = "❌ Prepare failed: " . $conn->error;
        }
    } else {
        $msg = "⚠️ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        h2 {
            text-align: center;
        }
        form {
            max-width: 400px;
            margin: auto;
        }
        label, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type=text] {
            padding: 8px;
            border: 1px solid #ccc;
        }
        input[type=submit] {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        .msg {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            color: green;
        }
        .msg.error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Add a New Book</h2>
    <form method="POST" action="">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Author:</label>
        <input type="text" name="author" required>

        <label>Genre:</label>
        <input type="text" name="genre" required>

        <input type="submit" value="Add Book">
    </form>

    <?php if ($msg): ?>
        <div class="msg <?= str_starts_with($msg, '❌') || str_starts_with($msg, '⚠️') ? 'error' : '' ?>">
            <?= htmlspecialchars($msg) ?>
        </div>

      <div style="text-align:center; margin-top:20px;">
    <a href="index.php" style="
        display:inline-block;
        padding:10px 18px;
        background-color:#007bff;
        color:white;
        text-decoration:none;
        border-radius:6px;
        font-weight:bold;
    ">
        🏠 Back to Home
    </a>
</div>
    <?php endif; ?>
</body>
</html>
