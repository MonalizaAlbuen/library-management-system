<?php
header("Content-Type: application/json; charset=UTF-8");
header("X-Content-Type-Options: nosniff");

include "../config/db_config.php";

$sql = "
SELECT 
    ib.id,
    ib.book_id,
    ib.student_sid,
    ib.issue_date,
    ib.return_date,
    b.title,
    b.author
FROM issued_books ib
LEFT JOIN books b ON ib.book_id = b.id
ORDER BY ib.issue_date DESC
";

$result = $conn->query($sql);

$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "id" => $row["id"],
            "book_id" => $row["book_id"],
            "student_sid" => $row["student_sid"],
            "title" => $row["title"],
            "author" => $row["author"],
            "issue_date" => $row["issue_date"],
            "return_date" => $row["return_date"]
        ];
    }

    echo json_encode([
        "status" => "success",
        "data" => $data
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} else {

    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>