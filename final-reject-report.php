<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

/* Search */

$where = " WHERE ais.interview_status='Rejected' ";
$params = [];

if (!empty($_GET['search'])) {

    $where .= " AND (
        aa.application_no LIKE :search
        OR aa.student_full_name LIKE :search
        OR aa.applicant_full_name LIKE :search
    )";

    $params[':search'] = "%" . $_GET['search'] . "%";
}

/* Selected Students Report */

$sql = "SELECT
            ais.interview_status,
            ais.remarks,
            ais.selected_date,

            aa.application_no,
            aa.student_full_name,
            aa.applicant_full_name,

            ai.interview_date,
            ai.interview_time

        FROM admission_interview_status ais

        INNER JOIN admission_applications aa
            ON ais.application_id = aa.id

        INNER JOIN admission_interviews ai
            ON ais.interview_id = ai.id

        $where

        ORDER BY ais.selected_date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <h3>
        Rejected Students Report
    </h3>

    <button onclick="window.print()"
            class="btn btn-success">

        Print Report

    </button>

</div>

<div class="card shadow mb-3">

    <div class="card-body">

        <form method="GET">

            <div class="row">

                <div class="col-md-10">

                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search Application No / Student Name / Applicant Name"
                           value="<?= $_GET['search'] ?? '' ?>">

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">

                        Search

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="alert alert-danger">

    <strong>Total Rejected Students :
        <?= count($students); ?>
    </strong>

</div>

<table class="table table-bordered table-striped">

    <thead class="table-danger">

        <tr>

            <th>No</th>
            <th>Application No</th>
            <th>Student Name</th>
            <th>Applicant Name</th>
            <th>Interview Date</th>
            <th>Interview Time</th>
            <th>Decision Date</th>
            <th>Status</th>
            <th>Remarks</th>

        </tr>

    </thead>

    <tbody>

    <?php
    $i = 1;

    if(!empty($students)){
        foreach($students as $row){
    ?>

        <tr>

            <td><?= $i++ ?></td>

            <td><?= htmlspecialchars($row['application_no']) ?></td>

            <td><?= htmlspecialchars($row['student_full_name']) ?></td>

            <td><?= htmlspecialchars($row['applicant_full_name']) ?></td>

            <td><?= date('Y-m-d', strtotime($row['interview_date'])) ?></td>

            <td><?= date('H:i', strtotime($row['interview_time'])) ?></td>

            <td><?= date('Y-m-d', strtotime($row['selected_date'])) ?></td>

            <td>

                <span class="badge bg-danger">

                    Rejected

                </span>

            </td>

            <td><?= htmlspecialchars($row['remarks']) ?></td>

        </tr>

    <?php
        }
    } else {
    ?>

        <tr>

            <td colspan="9" class="text-center text-danger">

                No Rejected Students Found.

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<script>
function printReport(){
    window.print();
}
</script>

<?php
$content = ob_get_clean();
include '../layout.php';
?>