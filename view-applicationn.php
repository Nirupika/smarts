<?php
ob_start();
include '../../init.php';


$conn = dbConnect();

/* Search & Filter */

$where = "";
$params = [];

if (!empty($_GET['search'])) {

    $where .= " AND (
        student_full_name LIKE :search
        OR applicant_full_name LIKE :search
    )";

    $params[':search'] = "%" . $_GET['search'] . "%";
}

if (!empty($_GET['status'])) {

    $where .= " AND status = :status";

    $params[':status'] = $_GET['status'];
}

/* Load Applications */

$sql = "SELECT admission_applications.*, admission_categories.category
        FROM admission_applications JOIN admission_categories ON admission_applications.category_id = admission_categories.id
        WHERE 1=1 $where
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h3 class="mb-3">
    Admission Applications
</h3>

<div class="card shadow mb-3">

    <div class="card-body">

        <form method="GET">

            <div class="row">

                <div class="col-md-5">

                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search Student / Applicant"
                        value="<?= @$_GET['search'] ?>">

                </div>

                <div class="col-md-3">

                    <select name="status"
                        class="form-control">

                        <option value="">
                            All Status
                        </option>

                        <option value="Pending">
                            Pending
                        </option>

                        <option value="Interview">
                            Interview
                        </option>
                        
                        <option value="Approved">
                            Approved
                        </option>

                       <option value="waiting-list">
                            Waiting List
                        </option> 

                        <option value="Rejected">
                            Rejected
                        </option>

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">
                        Search
                    </button>

                </div>

                <div class="col-md-2">

                    <a href="index.php"
                        class="btn btn-secondary w-100">

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

<table class="table table-bordered table-hover">

    <thead class="table-dark">

        <tr>

            <th>ID</th>

            <th>Student Name</th>

            <th>Applicant Name</th>

            <th>Category</th>

            <th>Date Applied</th>

            <th>Status</th>

            <th>
                Actions
            </th>

        </tr>

    </thead>

    <tbody>

        <?php foreach ($applications as $app) { ?>

            <tr>

                <td>
                    <?= $app['id'] ?>
                </td>

                <td>
                    <?= $app['student_full_name'] ?>
                </td>

                <td>
                    <?= $app['applicant_full_name'] ?>
                </td>

                <td>
                    <?= $app['category'] ?>
                </td>

                <td>
                    <?= date(
                        "Y-m-d",
                        strtotime($app['created_at'])
                    ) ?>
                </td>

                <td>

                    <?php

                    $status = $app['status'] ?? 'Pending' || ' ';

                    switch ($status) {

                        case 'Approved':
                            echo "<span class='badge bg-success'>Approved</span>";
                            break;

                        case 'Rejected':
                            echo "<span class='badge bg-danger'>Rejected</span>";
                            break;

                        case 'waiting-list':
                            echo "<span class='badge bg-info'>waiting-list</span>";
                            break;

                        case 'Interview':
                            echo "<span class='badge bg-warning'>Interview</span>";
                            break;

                        default:
                            echo "<span class='badge bg-secondary'>Pending</span>";
                    }
                    ?>

                </td>
                <td>

                    <a href="<?= WEB_URL ?>parent/view-application.php?id=<?= $app['id'] ?>"
                        class="btn btn-info btn-sm">
                        View
                    </a>
                    
                    

                    <a href="accept.php?id=<?= $app['id'] ?>"
                        class="btn btn-success btn-sm"
                        onclick="return confirm('Accept this application?')">
                        Accept
                    </a>

                    

                    <a href="reject.php?id=<?= $app['id'] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Reject this application?')">
                        Reject
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