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

/* -----------------------
   ISSUE BOOK
------------------------*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $book_id = $_POST['book_id'] ?? null;
    $student_id = $_POST['student_id'] ?? null;

    if ($book_id && $student_id) {

        $stmt = $conn->prepare("
            INSERT INTO issued_books (book_id, student_id, issue_date, return_date)
            VALUES (?, ?, CURDATE(), NULL)
        ");

        if ($stmt) {
            $stmt->bind_param("ii", $book_id, $student_id);

            if ($stmt->execute()) {
                $msg = "✅ Book issued successfully!";
            } else {
                $msg = "❌ Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $msg = "❌ Prepare failed: " . $conn->error;
        }

    } else {
        $msg = "⚠️ Please select both book and student.";
    }
}

/* -----------------------
   FETCH BOOKS
------------------------*/
$books = $conn->query("SELECT id, title FROM books");
if (!$books) {
    die("❌ SQL error (books): " . $conn->error);
}

/* -----------------------
   FETCH STUDENTS
------------------------*/
$students = $conn->query("SELECT id, name FROM students");
if (!$students) {
    die("❌ SQL error (students): " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
        }

        form {
            max-width: 420px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 6px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type=submit] {
            width: 100%;
            margin-top: 20px;
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type=submit]:hover {
            background-color: #0056b3;
        }

        .msg {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>

<h2>📚 Issue a Book</h2>

<form method="POST">

    <label>Select Book:</label>
    <select name="book_id" required>
        <option value="">-- Choose a Book --</option>
        <?php while ($row = $books->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= htmlspecialchars($row['title']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Select Student:</label>
    <select name="student_id" required>
        <option value="">-- Choose Student --</option>
        <?php while ($row = $students->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= htmlspecialchars($row['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input type="submit" value="Issue Book">

</form>

<?php if ($msg): ?>
    <p class="msg <?= str_starts_with($msg, '❌') || str_starts_with($msg, '⚠️') ? 'error' : 'success' ?>">
        <?= htmlspecialchars($msg) ?>
    </p>
<?php endif; ?>

</body>
</html>