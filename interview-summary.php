<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

/* Summary Statistics */

$totalInterviewed = $conn->query("
    SELECT COUNT(*)
    FROM admission_interview_status
")->fetchColumn();

$totalSelected = $conn->query("
    SELECT COUNT(*)
    FROM admission_interview_status
    WHERE interview_status='Accepted'
")->fetchColumn();

$totalWaiting = $conn->query("
    SELECT COUNT(*)
    FROM admission_interview_status
    WHERE interview_status='Waiting List'
")->fetchColumn();

$totalRejected = $conn->query("
    SELECT COUNT(*)
    FROM admission_interview_status
    WHERE interview_status='Rejected'
")->fetchColumn();

/* Percentages */

$selectedPercentage = ($totalInterviewed > 0)
    ? round(($totalSelected/$totalInterviewed)*100,2)
    : 0;

$waitingPercentage = ($totalInterviewed > 0)
    ? round(($totalWaiting/$totalInterviewed)*100,2)
    : 0;

$rejectedPercentage = ($totalInterviewed > 0)
    ? round(($totalRejected/$totalInterviewed)*100,2)
    : 0;
?>

<div class="d-flex justify-content-between mb-4">

    <h3>
        Interview Summary Report
    </h3>

    <button onclick="window.print()"
            class="btn btn-success">

        Print Report

    </button>

</div>

<div class="alert alert-info">

    This report summarizes the outcome of the admission interview process.

</div>

<table class="table table-bordered">

    <thead class="table-dark">

        <tr>

            <th width="60%">Description</th>

            <th width="40%" class="text-center">

                Total

            </th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td>Total Interviewed Students</td>

            <td class="text-center">

                <strong><?= $totalInterviewed ?></strong>

            </td>

        </tr>

        <tr>

            <td>Total Selected Students</td>

            <td class="text-center text-success">

                <strong><?= $totalSelected ?></strong>

            </td>

        </tr>

        <tr>

            <td>Total Waiting List Students</td>

            <td class="text-center text-primary">

                <strong><?= $totalWaiting ?></strong>

            </td>

        </tr>

        <tr>

            <td>Total Rejected Students</td>

            <td class="text-center text-danger">

                <strong><?= $totalRejected ?></strong>

            </td>

        </tr>

    </tbody>

</table>

<br>

<h4>
    Selection Statistics
</h4>

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

            <td>Selected</td>

            <td class="text-center">

                <?= $totalSelected ?>

            </td>

            <td class="text-center">

                <?= $selectedPercentage ?>%

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

<div class="card border-info">

    <div class="card-header bg-info text-white">

        Summary

    </div>

    <div class="card-body">

        <ul>

            <li>Total Interviewed Students :
                <strong><?= $totalInterviewed ?></strong>
            </li>

            <li>Total Selected Students :
                <strong><?= $totalSelected ?></strong>
            </li>

            <li>Total Waiting List Students :
                <strong><?= $totalWaiting ?></strong>
            </li>

            <li>Total Rejected Students :
                <strong><?= $totalRejected ?></strong>
            </li>

            <li>Overall Selection Rate :
                <strong><?= $selectedPercentage ?>%</strong>
            </li>

        </ul>

    </div>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>