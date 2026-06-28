<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$sql = "SELECT application_no,
               student_full_name,
               applicant_full_name,
               created_at
        FROM admission_applications
        WHERE status='interview'
        ORDER BY student_full_name";

$sql = "SELECT
    ai.interview_date,
    ai.interview_time,
    ai.venue,
    aa.application_no,
    aa.student_full_name,
    aa.applicant_full_name
FROM admission_interviews ai
INNER JOIN admission_applications aa
ON ai.application_id = aa.id
ORDER BY ai.interview_date";


$stmt = $conn->prepare($sql);
$stmt->execute();

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Interview Students Report</h3>

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
        <th>Interview Date</th>
        <th>Interview Time</th>     
        <th>Venue</th>


    </tr>

</thead>

<tbody>

<?php $i=1; foreach($records as $row){ ?>

    <tr>

        <td><?= $i++ ?></td>

        <td><?= $row['application_no'] ?></td>
        <td><?= $row['student_full_name'] ?></td>
        <td><?= $row['applicant_full_name'] ?></td>
        <td><?= $row['interview_date'] ?></td>
        <td><?= $row['interview_time'] ?></td>
        <td><?= $row['venue'] ?></td>

    </tr>

<?php } ?>

</tbody>

</table>

<?php
$content = ob_get_clean();
include '../layout.php';
?>
