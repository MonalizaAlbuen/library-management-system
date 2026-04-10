<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config/db_config.php';

/* -----------------------
   FETCH STUDENTS FROM API
------------------------*/
function fetchStudentsFromAPI() {
    $url = "http://localhost/Student-Management-System/api/get_students.php";

    $response = @file_get_contents($url);
    if ($response === false) return [];

    $data = json_decode($response, true);
    return $data['data'] ?? [];
}

/* -----------------------
   BUILD STUDENT MAP (SID => FULL NAME)
------------------------*/
$students = fetchStudentsFromAPI();

$studentMap = [];

foreach ($students as $s) {
    // supports fname + lname format
    if (isset($s['fname']) && isset($s['lname'])) {
        $studentMap[$s['sid']] = $s['fname'] . ' ' . $s['lname'];
    }
    // fallback if API uses "name" only
    else {
        $studentMap[$s['sid']] = $s['name'] ?? 'Unknown Student';
    }
}

/* -----------------------
   FETCH ISSUED LOGS
------------------------*/
$sql = "
    SELECT 
        ib.id,
        b.title,
        ib.student_sid,
        ib.issue_date,
        ib.return_date
    FROM issued_books ib
    JOIN books b ON ib.book_id = b.id
    ORDER BY ib.id DESC
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
            background: #f8f9fa;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
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

        .status {
            font-weight: bold;
        }

        .returned {
            color: green;
        }

        .not-returned {
            color: red;
        }

        small {
            display: block;
            color: #555;
            margin-top: 4px;
        }
    </style>
</head>
<body>

<h2>📋 Issued Books Logs</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Book Title</th>
        <th>Student</th>
        <th>Issue Date</th>
        <th>Return Date</th>
        <th>Status</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>

        <?php
            $sid = $row['student_sid'];
            $fullName = $studentMap[$sid] ?? 'Unknown Student';
        ?>

        <tr>
            <td><?= $row['id'] ?></td>

            <td><?= htmlspecialchars($row['title']) ?></td>

            <td>
                <?= htmlspecialchars($sid) ?>
                <small><?= htmlspecialchars($fullName) ?></small>
            </td>

            <td><?= $row['issue_date'] ?></td>

            <td><?= $row['return_date'] ?? 'Not Returned' ?></td>

            <td class="status <?= $row['return_date'] ? 'returned' : 'not-returned' ?>">
                <?= $row['return_date'] ? 'Returned' : 'Issued' ?>
            </td>
        </tr>

    <?php endwhile; ?>

</table>

</body>
</html>