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

function fetchStudentsFromAPI() {

    $url = "http://localhost/Student-Management-System/api/get_students.php";

    $response = file_get_contents($url);

    if ($response === false) {
        return [];
    }

    $data = json_decode($response, true);

    return $data['data'] ?? [];
}

/* ISSUE BOOK */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $book_id = $_POST['book_id'] ?? null;
    $student_sid = $_POST['student_sid'] ?? null;

    if ($book_id && $student_sid) {

        $stmt = $conn->prepare("
            INSERT INTO issued_books (book_id, student_sid, issue_date, return_date)
            VALUES (?, ?, CURDATE(), NULL)
        ");

        if ($stmt) {

            $stmt->bind_param("is", $book_id, $student_sid);

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

/* BOOKS */
$books = $conn->query("SELECT id, title FROM books");

/* STUDENTS */
$students = fetchStudentsFromAPI();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f9f9f9; }
        form { max-width: 420px; margin: auto; padding: 20px; background: #fff; border-radius: 8px; }
        label { display:block; margin-top:10px; font-weight:bold; }
        select, input { width:100%; padding:10px; margin-top:5px; }
        input[type=submit] { background:#007bff; color:white; border:none; margin-top:20px; cursor:pointer; }
        input[type=submit]:hover { background:#0056b3; }
        .msg { text-align:center; margin-top:15px; font-weight:bold; }
        .error { color:red; }
        .success { color:green; }
    </style>
</head>
<body>

<h2 style="text-align:center;">📚 Issue a Book</h2>

<form method="POST">

    <!-- BOOK -->
    <label>Select Book:</label>
    <select name="book_id" required>
        <option value="">-- Choose Book --</option>
        <?php while ($row = $books->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= htmlspecialchars($row['title']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <!-- STUDENT -->
    <label>Select Student:</label>
    <select name="student_sid" required>

        <option value="">-- Choose Student --</option>

        <?php if (!empty($students)): ?>
            <?php foreach ($students as $row): ?>
                <option value="<?= $row['sid'] ?>">
                    <?= htmlspecialchars($row['name']) ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option disabled>No students found (API issue)</option>
        <?php endif; ?>

    </select>

    <input type="submit" value="Issue Book">

</form>

<?php if ($msg): ?>
    <div style="max-width:420px;margin:15px auto;text-align:center;">
        <p class="<?= str_starts_with($msg,'❌') || str_starts_with($msg,'⚠️') ? 'error' : 'success' ?>">
            <?= htmlspecialchars($msg) ?>
        </p>
    </div>
<?php endif; ?>