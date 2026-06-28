<?php
ob_start();
include '../../init.php';
?>

<h3 class="mb-4">
    Final Selection Reports
</h3>

<div class="row">


<!-- Accepted Report -->

<div class="col-md-4 mb-4">

    <div class="card border-success shadow h-100">

        <div class="card-body text-center">

            <h4>✅</h4>

            <h5>Accepted Students Report</h5>

            <p class="text-muted">
                View all accepted students.
            </p>

            <a href="final-selection-report.php"
               class="btn btn-success">

                Generate Report

            </a>

        </div>

    </div>

</div>

<!-- Waiting List Report -->
<div class="col-md-4 mb-4">

    <div class="card border-primary shadow h-100">

        <div class="card-body text-center">

            <h4>📋</h4>

            <h5>Waiting List Report</h5>

            <p class="text-muted">
                View waiting list applicants.
            </p>

            <a href="final-waiting-report.php"
               class="btn btn-primary">

                Generate Report

            </a>

        </div>

    </div>

</div>

<!-- Rejected Report -->
<div class="col-md-4 mb-4">

    <div class="card border-danger shadow h-100">

        <div class="card-body text-center">

            <h4>❌</h4>

            <h5>Rejected Students Report</h5>

            <p class="text-muted">
                View rejected applications.
            </p>

            <a href="final-reject-report.php"
               class="btn btn-danger">

                Generate Report

            </a>

        </div>

    </div>

</div>

<!-- Summary Report -->
<div class="col-md-4 mb-4">

    <div class="card border-info shadow h-100">

        <div class="card-body text-center">

            <h4>📊</h4>

            <h5>Interview Summary Report</h5>

            <p class="text-muted">
                Overall Interview statistics.
            </p>

            <a href="interview-summary.php"
               class="btn btn-info">

                Generate Report

            </a>

        </div>

    </div>

</div>


</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>
