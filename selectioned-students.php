<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$sql = "SELECT
            ai.id AS interview_id,
            ai.interview_date,
            ai.interview_time,

            aa.id AS application_id,
            aa.application_no,
            aa.student_full_name,
            aa.applicant_full_name,

            ais.interview_status,
            ais.remarks,
            ais.selected_date

        FROM admission_interviews ai

        INNER JOIN admission_applications aa
            ON ai.application_id = aa.id

        LEFT JOIN admission_interview_status ais
            ON ais.application_id = aa.id

        ORDER BY ai.interview_date ASC,
                 ai.interview_time ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="mb-3">
    Student Selection
</h3>

<table class="table table-bordered table-hover">


    <thead class="table-dark">

        <tr>

            <th>Application No</th>

            <th>Student Name</th>

            <th>Applicant Name</th>

            <th>Interview Date</th>

            <th>Interview Time</th>

            <th>Status</th>
           
            <th width="350">
                Actions
            </th>

        </tr>

    </thead>

    <tbody>

        <?php foreach ($students as $row) { ?>

            <tr>

                <td>
                    <?= $row['application_no'] ?>
                </td>

                <td>
                    <?= $row['student_full_name'] ?>
                </td>

                <td>
                    <?= $row['applicant_full_name'] ?>
                </td>

                <td>
                    <?= $row['interview_date'] ?>
                </td>

                <td>
                    <?= $row['interview_time'] ?>
                </td>

                <td>

                    <?php

                    $status = $row['interview_status'];

                    switch ($status) {

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
                            echo "<span class='badge bg-warning'>Pending Decision</span>";
                    }

                    ?>

                </td>

                

                <td>

                    <a href="<?= WEB_URL ?>parent/view-application.php?id=<?= $row['application_id'] ?>"
                        class="btn btn-info btn-sm">

                        View

                    </a>

                    <a href="interview-finaldes.php?id=<?= $row['application_id'] ?>"
                        class="btn btn-secondary btn-sm">

                        Final Decision

                    </a>

                </td>
            </tr>



        <?php } ?>

    </tbody>

</table>

<a href="interview-status.php"
    class="btn btn-secondary">

    View Status

</a>

</div>

</div>

</div>


<?php
$content = ob_get_clean();
include '../layout.php';
?>