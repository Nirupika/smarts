<?php

include '../../init.php';

$conn = dbConnect();

$id = $_GET['id'] ?? 0;

$sql = "UPDATE admission_applications
        SET status='Approved'
        WHERE id=:id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

$sql = "SELECT *
FROM admission_applications
WHERE status='Approved'
ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$accepted_applications = $stmt->fetchAll(PDO::FETCH_ASSOC);


header("Location:selection-dashboard.php");
exit;

