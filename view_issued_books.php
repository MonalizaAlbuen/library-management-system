<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require 'config/db_config.php';

$sql = "
SELECT 
    ib.id,
    b.title AS book_title,
    s.name AS student_name,
    ib.issue_date,
    ib.return_date
FROM issued_books ib
JOIN books b ON ib.book_id = b.id
JOIN students s ON ib.student_id = s.id
";

$result = $conn->query($sql);

if (!$result) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issued Books Logs</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
            background-color: #f5f5f5;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .empty {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>📋 Issued Books Logs</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Book</th>
        <th>Student</th>
        <th>Issue Date</th>
        <th>Return Date</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['book_title']) ?></td>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= $row['issue_date'] ?></td>
                <td><?= $row['return_date'] ?? 'Not Returned' ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No issued books found</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>