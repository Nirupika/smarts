<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$sql = "SELECT
            ai.*,
            aa.application_no,
            aa.student_full_name,
            aa.applicant_full_name
        FROM admission_interviews ai
        INNER JOIN admission_applications aa
            ON ai.application_id = aa.id
        ORDER BY ai.interview_date ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$interviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Interview Schedule List</h3>

<table class="table table-bordered table-hover">


<thead class="table-warning">

    <tr>

        <th>Application No</th>
        <th>Student Name</th>
        <th>Applicant Name</th>
        <th>Interview Date</th>
        <th>Interview Time</th>
        <th>Venue</th>

    </tr>

</thead>

<tbody>

<?php foreach($interviews as $row){ ?>

    <tr>

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
