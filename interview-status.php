<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$sql = "SELECT
            ais.id,
            ais.interview_status,
            ais.remarks,
            ais.selected_by,
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

        ORDER BY ais.selected_date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="mb-3">
    Interview Status List
</h3>

<table class="table table-bordered table-hover">

    <thead class="table-dark">

        <tr>

            <th>Application No</th>
            <th>Student Name</th>
            <th>Applicant Name</th>
            <th>Interview Date</th>
            <th>Interview Time</th>
            <th>Venue</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Decision Date</th>

        </tr>

    </thead>

    <tbody>

    <?php if (!empty($students)) { ?>

        <?php foreach ($students as $row) { ?>

            <tr>

                <td>
                    <?= htmlspecialchars($row['application_no']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['student_full_name']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['applicant_full_name']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['interview_date']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['interview_time']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['venue']) ?>
                </td>

                <td>

                    <?php

                    switch ($row['interview_status']) {

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

                <td>
                    <?= htmlspecialchars($row['remarks']) ?>
                </td>

                <td>
                    <?= date('Y-m-d H:i', strtotime($row['selected_date'])) ?>
                </td>

            </tr>

        <?php } ?>

    <?php } else { ?>

        <tr>

            <td colspan="9" class="text-center text-danger">

                No Interview Status Records Found

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<?php
$content = ob_get_clean();
include '../layout.php';
?>