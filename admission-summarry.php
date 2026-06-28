<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

/* Summary Counts */

$totalAccepted = $conn->query("
SELECT COUNT(*)
FROM admission_applications
WHERE status='Accepted'
")->fetchColumn();

$totalWaiting = $conn->query("
SELECT COUNT(*)
FROM admission_applications
WHERE status='Waiting List'
")->fetchColumn();

$totalRejected = $conn->query("
SELECT COUNT(*)
FROM admission_applications
WHERE status='Rejected'
")->fetchColumn();

$totalInterview = $conn->query("
SELECT COUNT(*)
FROM admission_interviews
")->fetchColumn();


/* Admission Decisions */

$sql = "SELECT
            aa.application_no,
            aa.student_full_name,
            aa.applicant_full_name,
            aa.status,
            aa.created_at
        FROM admission_applications aa
        WHERE aa.status IN ('Accepted','Waiting List','Rejected')
        ORDER BY aa.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* Interview List */

$sql2 = "SELECT
            aa.application_no,
            aa.student_full_name,
            ai.interview_date,
            ai.interview_time,
            ai.venue
        FROM admission_interviews ai
        INNER JOIN admission_applications aa
            ON ai.application_id=aa.id
        ORDER BY ai.interview_date ASC";

$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$interviews = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<h2 class="mb-4 text-center">
    Admission Summary Report
</h2>

<!-- Summary Cards -->

<div class="row mb-4">

    <div class="col-md-3">

        <div class="card border-success shadow">

            <div class="card-body text-center">

                <h5>Accepted</h5>

                <h2 class="text-success">
                    <?= $totalAccepted ?>
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-primary shadow">

            <div class="card-body text-center">

                <h5>Waiting List</h5>

                <h2 class="text-primary">
                    <?= $totalWaiting ?>
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-danger shadow">

            <div class="card-body text-center">

                <h5>Rejected</h5>

                <h2 class="text-danger">
                    <?= $totalRejected ?>
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-warning shadow">

            <div class="card-body text-center">

                <h5>Interviews</h5>

                <h2 class="text-warning">
                    <?= $totalInterview ?>
                </h2>

            </div>

        </div>

    </div>

</div>

<!-- Admission Status -->

<div class="card shadow mb-4">

    <div class="card-header bg-dark text-white">

        <h5 class="mb-0">
            Admission Decision Summary
        </h5>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-secondary">

                <tr>

                    <th>Application No</th>
                    <th>Student Name</th>
                    <th>Applicant Name</th>
                    <th>Date Applied</th>
                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

            <?php if(!empty($applications)){ ?>

                <?php foreach($applications as $row){ ?>

                <tr>

                    <td><?= htmlspecialchars($row['application_no']) ?></td>

                    <td><?= htmlspecialchars($row['student_full_name']) ?></td>

                    <td><?= htmlspecialchars($row['applicant_full_name']) ?></td>

                    <td><?= date('Y-m-d',strtotime($row['created_at'])) ?></td>

                    <td>

                        <?php

                        if($row['status']=="Accepted"){

                            echo "<span class='badge bg-success'>Accepted</span>";

                        }elseif($row['status']=="Waiting List"){

                            echo "<span class='badge bg-primary'>Waiting List</span>";

                        }else{

                            echo "<span class='badge bg-danger'>Rejected</span>";

                        }

                        ?>

                    </td>

                </tr>

                <?php } ?>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<!-- Interview Schedule -->

<div class="card shadow">

    <div class="card-header bg-warning">

        <h5 class="mb-0">
            Interview Schedule
        </h5>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-warning">

                <tr>

                    <th>Application No</th>
                    <th>Student Name</th>
                    <th>Interview Date</th>
                    <th>Interview Time</th>
                    <th>Venue</th>

                </tr>

            </thead>

            <tbody>

            <?php if(!empty($interviews)){ ?>

                <?php foreach($interviews as $row){ ?>

                <tr>

                    <td><?= htmlspecialchars($row['application_no']) ?></td>

                    <td><?= htmlspecialchars($row['student_full_name']) ?></td>

                    <td><?= $row['interview_date'] ?></td>

                    <td><?= $row['interview_time'] ?></td>

                    <td><?= htmlspecialchars($row['venue']) ?></td>

                </tr>

                <?php } ?>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<div class="text-center mt-4">

    <button onclick="window.print()"
            class="btn btn-success">

        Print Report

    </button>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>