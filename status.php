<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$sql = "SELECT admission_applications.*, admission_categories.category
        FROM admission_applications JOIN admission_categories ON admission_applications.category_id = admission_categories.id
        where parent_id = :parent_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':parent_id', $_SESSION['user_id']);
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h3>Admission Applications</h3>

<a href="create.php" class="btn btn-primary mb-3">
    New Application
</a>

<table class="table table-bordered table-striped">

    <thead class="table-dark">

        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Student Name</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Applicant Name</th>
            <th>Mobile No</th>
            <th>Status</th>
            <th>Created Date</th>
            <th width="180">Actions</th>
        </tr>

    </thead>

    <tbody>

    <?php foreach($applications as $app){ ?>

        <tr>

            <td><?= $app['id'] ?></td>

            <td><?= $app['category'] ?></td>
                    
            <td><?= $app['student_full_name'] ?></td>

            <td><?= $app['student_gender'] ?></td>

            <td><?= $app['student_dob'] ?></td>

            <td><?= $app['applicant_full_name'] ?></td>

            <td><?= $app['mobile_no'] ?></td>

            <td>

                <?php if($app['status']=="Pending"){ ?>

                    <span class="badge bg-warning">
                        Pending
                    </span>

                <?php }elseif($app['status']=="Approved"){ ?>

                    <span class="badge bg-success">
                        Approved
                    </span>

                <?php }elseif($app['status']=="Rejected"){ ?>

                    <span class="badge bg-danger">
                        Rejected
                    </span>

                <?php }else{ ?>

                    <span class="badge bg-secondary">
                        <?= $app['status'] ?>
                    </span>

                <?php } ?>

            </td>

            <td><?= $app['created_at'] ?></td>

            <td>

                <a href="view-application.php?id=<?= $app['id'] ?>"
                   class="btn btn-sm btn-info">
                    View
                </a>

                <a href="edit-application.php?id=<?= $app['id'] ?>"
                   class="btn btn-sm btn-primary">
                    Edit
                </a>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<?php
$content = ob_get_clean();
include '../layout.php';
?>