<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$sql = "SELECT application_no,
               student_full_name,
               applicant_full_name,
               created_at
        FROM admission_applications
        WHERE status='waiting-list'
        ORDER BY student_full_name";

$stmt = $conn->prepare($sql);
$stmt->execute();

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Waiting List Students Report</h3>

<button onclick="window.print()"
     class="btn btn-success mb-3">
Print Report </button>

<table class="table table-bordered">

<thead class="table-success">

    <tr>

        <th>#</th>
        <th>Application No</th>
        <th>Student Name</th>
        <th>Applicant Name</th>

    </tr>

</thead>

<tbody>

<?php $i=1; foreach($records as $row){ ?>

    <tr>

        <td><?= $i++ ?></td>

        <td><?= $row['application_no'] ?></td>

        <td><?= $row['student_full_name'] ?></td>

        <td><?= $row['applicant_full_name'] ?></td>

    </tr>

<?php } ?>

</tbody>

</table>

<?php
$content = ob_get_clean();
include '../layout.php';
?>
