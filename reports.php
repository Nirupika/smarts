<?php
ob_start();
include '../../init.php';
?>

<h3 class="mb-4">
     Reports Dashboard
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

            <a href="accepted-report.php"
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

            <a href="waiting-report.php"
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

            <a href="rejected-report.php"
               class="btn btn-danger">

                Generate Report

            </a>

        </div>

    </div>

</div>

<!-- Interview Report -->
<div class="col-md-4 mb-4">

    <div class="card border-warning shadow h-100">

        <div class="card-body text-center">

            <h4>🗓️</h4>

            <h5>Interview Schedule Report</h5>

            <p class="text-muted">
                View scheduled interviews.
            </p>

            <a href="interview-report.php"
               class="btn btn-warning">

                Generate Report

            </a>

        </div>

    </div>

</div>

<!-- Final Selection Report -->
<div class="col-md-4 mb-4">

    <div class="card border-dark shadow h-100">

        <div class="card-body text-center">

            <h4>🎓</h4>

            <h5>Final Selection Report</h5>

            <p class="text-muted">
                Accepted, Waiting and Rejected students.
            </p>

            <a href="interview-reports-dash.php"
               class="btn btn-dark">

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

            <h5>Application Summary Report</h5>

            <p class="text-muted">
                Overall admission statistics.
            </p>

            <a href="admission-summary.php"
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
