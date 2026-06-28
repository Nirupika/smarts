<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

/* Application Statistics */

$totalApplications = $conn->query("
    SELECT COUNT(*)
    FROM admission_applications
")->fetchColumn();

$totalAccepted = $conn->query("
    SELECT COUNT(*)
    FROM admission_applications
    WHERE status='Approved'
")->fetchColumn();

$totalWaiting = $conn->query("
    SELECT COUNT(*)
    FROM admission_applications
    WHERE status='waiting-list'
")->fetchColumn();

$totalRejected = $conn->query("
    SELECT COUNT(*)
    FROM admission_applications
    WHERE status='Rejected'
")->fetchColumn();

/* Percentages */

$acceptedPercentage = ($totalApplications > 0)
    ? round(($totalAccepted / $totalApplications) * 100, 2)
    : 0;

$waitingPercentage = ($totalApplications > 0)
    ? round(($totalWaiting / $totalApplications) * 100, 2)
    : 0;

$rejectedPercentage = ($totalApplications > 0)
    ? round(($totalRejected / $totalApplications) * 100, 2)
    : 0;
?>

<div class="d-flex justify-content-between mb-4">

    <h3>
        Application Summary Report
    </h3>

    <button onclick="window.print()"
            class="btn btn-success">

        Print Report

    </button>

</div>

<div class="alert alert-info">

    This report summarizes the overall admission application status.

</div>

<table class="table table-bordered">

    <thead class="table-dark">

        <tr>

            <th width="70%">Description</th>
            <th width="30%" class="text-center">Total</th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td>Total Applications</td>

            <td class="text-center">
                <strong><?= $totalApplications ?></strong>
            </td>

        </tr>

        <tr>

            <td>Total Approved Applications</td>

            <td class="text-center text-success">
                <strong><?= $totalAccepted ?></strong>
            </td>

        </tr>

        <tr>

            <td>Total Waiting List Applications</td>

            <td class="text-center text-primary">
                <strong><?= $totalWaiting ?></strong>
            </td>

        </tr>

        <tr>

            <td>Total Rejected Applications</td>

            <td class="text-center text-danger">
                <strong><?= $totalRejected ?></strong>
            </td>

        </tr>

    </tbody>

</table>

<br>

<h4>Application Statistics</h4>

<table class="table table-bordered">

    <thead class="table-secondary">

        <tr>

            <th>Status</th>
            <th class="text-center">Count</th>
            <th class="text-center">Percentage</th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td>Approved</td>

            <td class="text-center">
                <?= $totalAccepted ?>
            </td>

            <td class="text-center">
                <?= $acceptedPercentage ?>%
            </td>

        </tr>

        <tr>

            <td>Waiting List</td>

            <td class="text-center">
                <?= $totalWaiting ?>
            </td>

            <td class="text-center">
                <?= $waitingPercentage ?>%
            </td>

        </tr>

        <tr>

            <td>Rejected</td>

            <td class="text-center">
                <?= $totalRejected ?>
            </td>

            <td class="text-center">
                <?= $rejectedPercentage ?>%
            </td>

        </tr>

    </tbody>

</table>

<br>

<div class="card border-primary">

    <div class="card-header bg-primary text-white">

        Summary

    </div>

    <div class="card-body">

        <ul>

            <li>
                Total Applications :
                <strong><?= $totalApplications ?></strong>
            </li>

            <li>
                Approved Applications :
                <strong><?= $totalAccepted ?></strong>
            </li>

            <li>
                Waiting List Applications :
                <strong><?= $totalWaiting ?></strong>
            </li>

            <li>
                Rejected Applications :
                <strong><?= $totalRejected ?></strong>
            </li>

            <li>
                Approval Rate :
                <strong><?= $acceptedPercentage ?>%</strong>
            </li>

        </ul>

    </div>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>