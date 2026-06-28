<?php
ob_start();
include '../../init.php';

if (!isset($_SESSION['logged_in']) || ($_SESSION['role_id'] != 3 && $_SESSION['role_id'] != 1)) {
    header("Location: ../login.php");
    exit();
}

$conn = dbConnect();

/* Statistics */

$totalInterviews = $conn->query("
SELECT COUNT(*)
FROM admission_interviews
")->fetchColumn();

$selectedStudents = $conn->query("
SELECT COUNT(*)
FROM admission_interview_status
WHERE interview_status='Accepted'
")->fetchColumn();

$waitingStudents = $conn->query("
SELECT COUNT(*)
FROM admission_interview_status
WHERE interview_status='Waiting List'
")->fetchColumn();

$rejectedStudents = $conn->query("
SELECT COUNT(*)
FROM admission_interview_status
WHERE interview_status='Rejected'
")->fetchColumn();

?>

<section class="section-padding">

<div class="container">

    <div class="card shadow border-0 mb-4">

        <div class="card-body">

            <h2>
                Interview Dashboard
            </h2>

            <p class="text-muted">
                Manage interview results, student selection and reports.
            </p>

        </div>

    </div>

    <!-- Statistics -->

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h6>Total Interviews</h6>

                    <h2 class="text-primary">
                        <?= $totalInterviews ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h6>Selected</h6>

                    <h2 class="text-success">
                        <?= $selectedStudents ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h6>Waiting List</h6>

                    <h2 class="text-primary">
                        <?= $waitingStudents ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h6>Rejected</h6>

                    <h2 class="text-danger">
                        <?= $rejectedStudents ?>
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <!-- Functions -->

    <div class="row">

        <!-- Interview Status -->

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow ">

                <div class="card-body text-center">

                    <h5>Interview Status</h5>

                    <p>
                        View all interview decisions and remarks.
                    </p>

                    <a href="selectioned-students.php"
                       class="btn btn-warning">

                        View Status

                    </a>

                </div>

            </div>

        </div>

        <!-- Selected -->

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow ">

                <div class="card-body text-center">

                    <h5>Selected Students</h5>

                    <p>
                        View students selected after the interview.
                    </p>

                    <a href="final-selected-list.php"
                       class="btn btn-success">

                        View List

                    </a>

                </div>

            </div>

        </div>

        <!-- Waiting -->

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow ">

                <div class="card-body text-center">

                    <h5>Waiting List</h5>

                    <p>
                        View students placed on the waiting list.
                    </p>

                    <a href="final-waiting-list.php"
                       class="btn btn-primary">

                        View List

                    </a>

                </div>

            </div>

        </div>

        <!-- Rejected -->

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow ">

                <div class="card-body text-center">

                    <h5>Rejected Students</h5>

                    <p>
                        View rejected students after the interview.
                    </p>

                    <a href="final-reject-list.php"
                       class="btn btn-danger">

                        View List

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</section>

<?php
$content = ob_get_clean();
include '../layout.php';
?>