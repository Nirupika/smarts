<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

/* Search */

$where = " WHERE ais.interview_status='Accepted' ";
$params = [];

if (!empty($_GET['search'])) {

    $where .= " AND (
        aa.application_no LIKE :search
        OR aa.student_full_name LIKE :search
        OR aa.applicant_full_name LIKE :search
    )";

    $params[':search'] = "%" . $_GET['search'] . "%";
}

/* Load Selected Students */

$sql = "SELECT
            ais.id,
            ais.interview_status,
            ais.remarks,
            ais.selected_date,

            aa.id AS application_id,
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

<h3 class="mb-3">
    Selected Students
</h3>

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

<div class="alert alert-success">

    Total Selected Students :
    <strong><?= count($students) ?></strong>

</div>

<table class="table table-bordered table-hover">

    <thead class="table-success">

        <tr>

            <th>Application No</th>

            <th>Student Name</th>

            <th>Applicant Name</th>

            <th>Interview Date</th>

            <th>Decision Date</th>

            <th>Status</th>

            <th>Remarks</th>

            <th width="120">
                Action
            </th>

        </tr>

    </thead>

    <tbody>

    <?php if(!empty($students)){ ?>

        <?php foreach($students as $row){ ?>

        <tr>

            <td>

                <span class="badge bg-dark">

                    <?= htmlspecialchars($row['application_no']) ?>

                </span>

            </td>

            <td>

                <?= htmlspecialchars($row['student_full_name']) ?>

            </td>

            <td>

                <?= htmlspecialchars($row['applicant_full_name']) ?>

            </td>

            <td>

                <?= date('Y-m-d', strtotime($row['interview_date'])) ?>

            </td>

            <td>

                <?= date('Y-m-d', strtotime($row['selected_date'])) ?>

            </td>

            <td>

                <span class="badge bg-success">

                    Selected

                </span>

            </td>

            <td>

                <?= htmlspecialchars($row['remarks']) ?>

            </td>

            <td>

                <a href="<?= WEB_URL ?>parent/view-application.php?id=<?= $row['application_id'] ?>"
                    class="btn btn-info btn-sm">

                    View

                </a>

            </td>

        </tr>

        <?php } ?>

    <?php } else { ?>

        <tr>

            <td colspan="8" class="text-center text-danger">

                No Selected Students Found

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<?php
$content = ob_get_clean();
include '../layout.php';
?>