<?php

include '../../init.php';

$conn = dbConnect();

$id = $_GET['id'] ?? 0;

$sql = "UPDATE admission_applications
        SET status='Rejected'
        WHERE id=:id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

$sql = "SELECT *
FROM admission_applications
WHERE status='Rejected'
ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$rejected_applications = $stmt->fetchAll(PDO::FETCH_ASSOC);


header("Location:selection-dashboard.php");
exit;