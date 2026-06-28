<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

/* Search */

$where = " WHERE status='Approved' ";
$params = [];

if (!empty($_GET['search'])) {

    $where .= " AND (
        student_full_name LIKE :search
        OR applicant_full_name LIKE :search
        OR application_no LIKE :search
    )";

    $params[':search'] = "%" . $_GET['search'] . "%";
}

/* Load Approved Applications */

$sql = "SELECT *
        FROM admission_applications
        $where
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="mb-3">
    Approved Applications
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

                <button class="btn btn-success w-100">
                    Search
                </button>

            </div>

        </div>

    </form>

</div>


</div>

<div class="alert alert-success">


Total Approved Applications :
<strong><?= count($applications) ?></strong>


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
            Action </th>

        <th> Waiting List </th>

        <th> Interview </th>

    </tr>

</thead>

<tbody>

    <?php if(!empty($applications)){ ?>

        <?php foreach($applications as $app){ ?>

            <tr>

                <td>
                    <span class="badge bg-dark">
                        <?= htmlspecialchars($app['application_no']) ?>
                    </span>
                </td>

                <td>
                    <?= htmlspecialchars($app['student_full_name']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($app['applicant_full_name']) ?>
                </td>

                <td>
                    <?= date('Y-m-d', strtotime($app['created_at'])) ?>
                </td>

                <td>
                    <span class="badge bg-success">
                        Approved    
                    </span>
                </td>

                <td>

                    <a href="<?= WEB_URL ?>parent/view-application.php?id=<?= $app['id'] ?>"
                        class="btn btn-info btn-sm">

                        View

                    </a>
                    </td>

                    <td>
                    <a href="waiting-list.php?id=<?= $app['id'] ?>"
                        class="btn btn-info btn-sm">

                        Waiting List

                    </a>

                </td>

                <td>
                    <a href="interview.php?id=<?= $app['id'] ?>"
                        class="btn btn-info btn-sm">

                        Interview

                    </a                     
                    
                </td >   

            </tr>

        <?php } ?>

    <?php } else { ?>

        <tr>

            <td colspan="6" class="text-center text-danger">

                No Approved Applications Found

            </td>

        </tr>

    <?php } ?>

</tbody>
```

</table>

<?php
$content = ob_get_clean();
include '../layout.php';
?>
