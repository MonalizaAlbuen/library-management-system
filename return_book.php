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
}

/* -----------------------
   FETCH ISSUED BOOKS
------------------------*/
$sql = "
    SELECT 
        ib.id,
        b.title,
        ib.student_sid
    FROM issued_books ib
    JOIN books b ON ib.book_id = b.id
    WHERE ib.return_date IS NULL
";

$result = $conn->query($sql);

if (!$result) {
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
            margin: 40px;
            background: #f8f9fa;
        }

        form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        input[type=submit] {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .msg {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">📥 Return Book</h2>

<form method="POST">

    <label>Select Issued Book:</label>
    <select name="issue_id" required>
        <option value="">-- Choose Book --</option>

        <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= htmlspecialchars($row['title']) ?> 
                (<?= htmlspecialchars($row['student_sid']) ?>)
            </option>
        <?php endwhile; ?>

    </select>

    <input type="submit" value="Return Book">

</form>

<?php if ($msg): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

</body>
</html>