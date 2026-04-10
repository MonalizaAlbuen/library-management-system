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
   RETURN BOOK
------------------------*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $issue_id = $_POST['issue_id'] ?? null;

    if ($issue_id) {

        $stmt = $conn->prepare("
            UPDATE issued_books 
            SET return_date = CURDATE()
            WHERE id = ?
        ");

        if ($stmt) {
            $stmt->bind_param("i", $issue_id);

            if ($stmt->execute()) {
                $msg = "✅ Book returned successfully!";
            } else {
                $msg = "❌ Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $msg = "❌ Prepare failed: " . $conn->error;
        }

    } else {
        $msg = "⚠️ Please select a book.";
    }
}

/* -----------------------
   FETCH ISSUED BOOKS
------------------------*/
$issued = $conn->query("
    SELECT 
        ib.id,
        b.title,
        s.name AS student_name
    FROM issued_books ib
    JOIN books b ON ib.book_id = b.id
    JOIN students s ON ib.student_id = s.id
    WHERE ib.return_date IS NULL
");

if (!$issued) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <style>
        body {
            font-family: Arial;
            background: #f8f9fa;
            margin: 40px;
        }

        h2 {
            text-align: center;
        }

        form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        input[type=submit] {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        input[type=submit]:hover {
            background: #218838;
        }

        .msg {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<h2>📘 Return a Book</h2>

<form method="POST">

    <label>Select Issued Book:</label>
    <select name="issue_id" required>
        <option value="">-- Choose a Book --</option>

        <?php while ($row = $issued->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= htmlspecialchars($row['title']) ?> 
                (<?= htmlspecialchars($row['student_name']) ?>)
            </option>
        <?php endwhile; ?>

    </select>

    <input type="submit" value="Return Book">
</form>

<?php if ($msg): ?>
    <div class="msg <?= str_starts_with($msg, '❌') || str_starts_with($msg, '⚠️') ? 'error' : 'success' ?>">
        <?= htmlspecialchars($msg) ?>
    </div>
<?php endif; ?>

</body>
</html>