<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config/db_config.php';

/* =========================
   PAGINATION SETUP
========================= */
$limit = 5; // books per page

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// total records
$totalResult = $conn->query("SELECT COUNT(*) as total FROM books");
$totalRow = $totalResult->fetch_assoc();
$totalBooks = $totalRow['total'];

$totalPages = ceil($totalBooks / $limit);

// fetch paginated data
$sql = "SELECT * FROM books ORDER BY added_on DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if (!$result) {
    die("❌ SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library - View Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f8f9fa;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar input {
            padding: 8px;
            width: 60%;
            max-width: 400px;
            font-size: 16px;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f1f5ff;
        }
        tr:hover {
            background-color: #d8ecff;
        }
        .empty {
            text-align: center;
            margin-top: 50px;
            font-style: italic;
            color: #777;
        }
        @media (max-width: 600px) {
            table, th, td {
                font-size: 14px;
            }
            .search-bar input {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <h2>📚 Library Books</h2>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="🔍 Search by title, author, or genre...">
    </div>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>📖 Title</th>
                    <th>✍️ Author</th>
                    <th>🎯 Genre</th>
                    <th>🕓 Added On</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['author']) ?></td>
                        <td><?= htmlspecialchars($row['genre']) ?></td>
                        <td><?= htmlspecialchars($row['added_on']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty">No books added yet.</p>
    <?php endif; ?>

    <!-- PAGINATION BUTTONS -->
    <div style="text-align:center; margin-top:20px;">

        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" style="
                margin:5px;
                padding:8px 12px;
                background:#007bff;
                color:white;
                text-decoration:none;
                border-radius:5px;">
                ◀ Prev
            </a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" style="
                margin:3px;
                padding:8px 12px;
                text-decoration:none;
                border-radius:5px;
                <?= ($i == $page) ? 'background:#28a745;color:white;' : 'background:#f1f1f1;color:black;' ?>
            ">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" style="
                margin:5px;
                padding:8px 12px;
                background:#007bff;
                color:white;
                text-decoration:none;
                border-radius:5px;">
                Next ▶
            </a>
        <?php endif; ?>

    </div>

    <script>
        const searchInput = document.getElementById("searchInput");
        searchInput.addEventListener("keyup", function () {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll("table tbody tr");

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>

    <!-- BACK TO HOME BUTTON -->
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