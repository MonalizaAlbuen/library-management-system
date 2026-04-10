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
   BUILD STUDENT MAP
------------------------*/
$students = fetchStudentsFromAPI();

$studentMap = [];

foreach ($students as $s) {
    if (isset($s['fname']) && isset($s['lname'])) {
        $studentMap[$s['sid']] = $s['fname'] . ' ' . $s['lname'];
    } else {
        $studentMap[$s['sid']] = $s['name'] ?? 'Unknown Student';
    }
}

/* -----------------------
   PAGINATION SETUP
------------------------*/
$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// total records
$totalResult = $conn->query("SELECT COUNT(*) as total FROM issued_books");
$totalRow = $totalResult->fetch_assoc();
$totalLogs = $totalRow['total'];

$totalPages = ceil($totalLogs / $limit);

/* -----------------------
   FETCH ISSUED LOGS (PAGINATED)
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
    LIMIT $limit OFFSET $offset
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

        .pagination {
            text-align:center;
            margin-top:20px;
        }

        .pagination a {
            margin:3px;
            padding:8px 12px;
            text-decoration:none;
            border-radius:5px;
            background:#f1f1f1;
            color:black;
            display:inline-block;
        }

        .pagination a.active {
            background:#28a745;
            color:white;
        }

        .pagination a.nav {
            background:#007bff;
            color:white;
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

<!-- PAGINATION -->
<div class="pagination">

    <?php if ($page > 1): ?>
        <a class="nav" href="?page=<?= $page - 1 ?>">◀ Prev</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="<?= ($i == $page) ? 'active' : '' ?>" href="?page=<?= $i ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a class="nav" href="?page=<?= $page + 1 ?>">Next ▶</a>
    <?php endif; ?>

</div>

<!-- BACK TO HOME -->
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

</body>
</html>