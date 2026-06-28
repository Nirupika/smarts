<?php
ob_start();
include '../../init.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: ../login.php");
    exit();
}

$conn = dbConnect();

$parent_id = $_SESSION['user_id'];

$sql = "SELECT
            ais.interview_status,
            ais.remarks,
            ais.selected_date,

            aa.application_no,
            aa.student_full_name,
            aa.applicant_full_name,

            ai.interview_date,
            ai.interview_time,
            ai.venue

        FROM admission_interview_status ais

        INNER JOIN admission_applications aa
            ON ais.application_id = aa.id

        INNER JOIN admission_interviews ai
            ON ais.interview_id = ai.id

        WHERE aa.parent_id = :parent_id

        ORDER BY ais.selected_date DESC

        LIMIT 1";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':parent_id' => $parent_id
]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h3 class="mb-4">
    My Interview Result
</h3>

<?php if($result){ ?>

<div class="card shadow">

    <div class="card-header bg-primary text-white">

        Interview Result

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="250">Application Number</th>
                <td><?= htmlspecialchars($result['application_no']) ?></td>
            </tr>

            <tr>
                <th>Student Name</th>
                <td><?= htmlspecialchars($result['student_full_name']) ?></td>
            </tr>

            <tr>
                <th>Applicant Name</th>
                <td><?= htmlspecialchars($result['applicant_full_name']) ?></td>
            </tr>

            <tr>
                <th>Interview Date</th>
                <td><?= htmlspecialchars($result['interview_date']) ?></td>
            </tr>

            <tr>
                <th>Interview Time</th>
                <td><?= htmlspecialchars($result['interview_time']) ?></td>
            </tr>

            <tr>
                <th>Venue</th>
                <td><?= htmlspecialchars($result['venue']) ?></td>
            </tr>

            <tr>
                <th>Interview Status</th>

                <td>

                    <?php

                    switch($result['interview_status']){

                        case 'Accepted':
                            echo "<span class='badge bg-success'>Accepted</span>";
                            break;

                        case 'Waiting List':
                            echo "<span class='badge bg-primary'>Waiting List</span>";
                            break;

                        case 'Rejected':
                            echo "<span class='badge bg-danger'>Rejected</span>";
                            break;

                        default:
                            echo "<span class='badge bg-secondary'>Pending</span>";
                    }

                    ?>

                </td>

            </tr>

            <tr>
                <th>Remarks</th>
                <td><?= nl2br(htmlspecialchars($result['remarks'])) ?></td>
            </tr>

            <tr>
                <th>Decision Date</th>
                <td><?= date('Y-m-d H:i', strtotime($result['selected_date'])) ?></td>
            </tr>

        </table>

    </div>

</div>

<?php } else { ?>

<div class="alert alert-warning">

    No interview result has been published yet.

</div>

<?php } ?>

<?php
$content = ob_get_clean();
include '../layout.php';
?>