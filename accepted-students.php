<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$sql = "SELECT
            application_no,
            student_full_name,
            applicant_full_name,
            category_id,
            created_at,
            status
        FROM admission_applications
        WHERE status='Accepted'
        ORDER BY student_full_name ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="mb-3">
    Accepted Selected Students
</h3>

<div class="alert alert-success">


Total Accepted Students :
<strong><?= count($students) ?></strong>


</div>

<table class="table table-bordered table-hover">


<thead class="table-success">

    <tr>

        <th>Application No</th>

        <th>Student Name</th>

        <th>Applicant Name</th>

        <th>Date Applied</th>

        <th>Status</th>

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
                <?= htmlspecialchars($row['application_no']) ?>
            </td>

            <td>
                <?= htmlspecialchars($row['student_full_name']) ?>
            </td>

            <td>
                <?= htmlspecialchars($row['applicant_full_name']) ?>
            </td>

            <td>
                <?= date('Y-m-d', strtotime($row['created_at'])) ?>
            </td>

            <td>
                <span class="badge bg-success">
                    Accepted
                </span>
            </td>

            <td>

                <a href="<?= WEB_URL ?>parent/view-application.php?id=<?= $row['id'] ?>"
                    class="btn btn-info btn-sm">

                    View

                </a>

            </td>

        </tr>

    <?php } ?>

<?php } else { ?>

    <tr>

        <td colspan="6" class="text-center text-danger">

            No Accepted Students Found

        </td>

    </tr>

<?php } ?>

</tbody>
</table>

<?php
$content = ob_get_clean();
include '../layout.php';
?>
