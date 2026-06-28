<?php
ob_start();
include '../../init.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role_id'] != 5 && $_SESSION['role_id'] != 1) {
    header("Location: ../dashboard.php?id=" . $_GET['id']);
    exit();
}

$conn = dbConnect();

$id = $_GET['id'] ?? 0;

$sql = "SELECT aa.*,
               u.first_name,
               u.last_name,
               aa.status,
               admission_categories.category AS admission_categories,
               admission_districts.districts AS district_name,
               admission_gsdivision.gs_division AS gs_division

        FROM admission_applications aa
        LEFT JOIN admission_categories ON aa.category_id = admission_categories.id
        LEFT JOIN users u ON aa.parent_id = u.id
        LEFT JOIN admission_districts ON aa.administrative_district = admission_districts.id
        LEFT JOIN admission_gsdivision ON aa.gs_division = admission_gsdivision.id
        WHERE aa.id = :id";



$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

$app = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$app) {
    die("Application Not Found");
}


/* ==========================
   LOAD CATEGORY NAME
   ========================== */

$category_name = '';

if (!empty($app['category'])) {

    $stmt = $conn->prepare("
        SELECT *
        FROM admission_categories
        WHERE id = ?
    ");

    $stmt->execute([$app['category']]);

    $cat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cat) {
        $category_name = $cat['category'];
    }
}

/* ==========================
   LOAD DISTRICT NAME
   ========================== */

$district_name = '';

if (!empty($app['administrative_district'])) {

    $stmt = $conn->prepare("
        SELECT *
        FROM admission_districts
        WHERE id = ?
    ");

    $stmt->execute([$app['administrative_district']]);

    $district = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($district) {
        $district_name = $district['districts'];
    }
}

/* ==========================
   LOAD GS DIVISION NAME
   ========================== */

$gs_name = '';

if (!empty($app['gs_division'])) {

    $stmt = $conn->prepare("
        SELECT *
        FROM admission_gsdivision
        WHERE id = ?
    ");

    $stmt->execute([$app['gs_division']]);

    $gs = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gs) {
        $gs_name = $gs['gs_division'];
    }
}
?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h3>Admission Application Details</h3>
        </div>

        <div class="card-body">

            <!-- Category -->

            <h4 class="text-primary border-bottom pb-2">
                Admission Category
            </h4>

            <p>
                <strong>Category :</strong>
                <?= $app['admission_categories'] ?>
                <?= $category_name ?>

            </p>

            <!-- Student Details -->

            <h4 class="text-primary border-bottom pb-2 mt-4">
                Student Details
            </h4>

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Full Name</th>
                    <td><?= $app['student_full_name'] ?></td>
                </tr>

                <tr>
                    <th>Name with Initials</th>
                    <td><?= $app['student_initial_name'] ?></td>
                </tr>

                <tr>
                    <th>Gender</th>
                    <td><?= $app['student_gender'] ?></td>
                </tr>

                <tr>
                    <th>Religion</th>
                    <td><?= $app['student_religion'] ?></td>
                </tr>

                <tr>
                    <th>Medium</th>
                    <td><?= $app['student_medium'] ?></td>
                </tr>

                <tr>
                    <th>Date of Birth</th>
                    <td><?= $app['student_dob'] ?></td>
                </tr>



            </table>

            <!-- Applicant Details -->

            <h4 class="text-primary border-bottom pb-2 mt-4">
                Applicant Details
            </h4>

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Full Name</th>
                    <td><?= $app['applicant_full_name'] ?></td>
                </tr>

                <tr>
                    <th>Name with Initials</th>
                    <td><?= $app['applicant_initial_name'] ?></td>
                </tr>

                <tr>
                    <th>Gender</th>
                    <td><?= $app['applicant_gender'] ?></td>
                </tr>

                <tr>
                    <th>NIC Number</th>
                    <td><?= $app['applicant_nic'] ?></td>
                </tr>

                <tr>
                    <th>Permanent Address</th>
                    <td><?= $app['permanent_address'] ?></td>
                </tr>

                <tr>
                    <th>Mobile No</th>
                    <td><?= $app['mobile_no'] ?></td>
                </tr>

                <tr>
                    <th>Whatsapp No</th>
                    <td><?= $app['whatsapp_no'] ?></td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td><?= $app['email'] ?></td>
                </tr>

                <tr>
                    <th>Administrative District</th>
                    <td> <?= $app['district_name'] ?></td>
                </tr>

                <tr>
                    <th>GS Division</th>
                    <td><?= $app['gs_division'] ?> </td>
                </tr>

            </table>

            <!-- Electoral Details -->

            <h4 class="text-primary border-bottom pb-2 mt-4">
                Electoral Registration Details
            </h4>

            <table class="table table-bordered">

                <thead class="table-light">
                    <tr>
                        <th>PD No</th>
                        <th>Serial No</th>
                        <th>Name</th>
                    </tr>
                </thead>

                <tbody>

                    <?php for ($i = 1; $i <= 5; $i++) { ?>

                        <tr>

                            <td>
                                <?= $app['pd_no_' . $i] ?>
                            </td>

                            <td>
                                <?= $app['serial_no_' . $i] ?>
                            </td>

                            <td>
                                <?= $app['name_' . $i] ?>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <!-- Nearby Schools -->

            <h4 class="text-primary border-bottom pb-2 mt-4">
                Nearby Schools
            </h4>

            <table class="table table-bordered">

                <tr>
                    <th width="30%">School 1</th>
                    <td><?= $app['nearby_school_1'] ?></td>
                </tr>

                <tr>
                    <th>School 2</th>
                    <td><?= $app['nearby_school_2'] ?></td>
                </tr>

                <tr>
                    <th>School 3</th>
                    <td><?= $app['nearby_school_3'] ?></td>
                </tr>

            </table>
            <img src="<?= WEB_URL ?>parent/uploads/location_maps/<?= $app['location_map'] ?>"
                width="300">

            <img src="<?= WEB_URL ?>parent/uploads/birth_certificates/<?= $app['birth_certificate_image'] ?>"
                width="300">
            <img src="<?= WEB_URL ?>parent/uploads/gs_certificates/<?= $app['gs_certificate_image'] ?>"
                <!-- Application Info -->

            <h4 class="text-primary border-bottom pb-2 mt-4">
                Application Information
            </h4>

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Application ID</th>
                    <td><?= $app['id'] ?></td>
                </tr>

                <tr>
                    <th>Submitted By</th>
                    <td>
                        <?= $app['first_name'] . ' ' . $app['last_name'] ?>
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>

                        <?php
                        if ($app['status'] == "Pending" || empty($app['status'])) {
                            echo "<span class='badge bg-warning'>Pending</span>";
                        } elseif ($app['status'] == "Approved") {
                            echo "<span class='badge bg-success'>Approved</span>";
                        } elseif ($app['status'] == "Rejected") {
                            echo "<span class='badge bg-danger'>Rejected</span>";
                        } elseif ($app['status'] == "waiting-list") {
                            echo "<span class='badge bg-info'>Waiting List</span>"; 
                        } elseif ($app['status'] == "Interview") {
                            echo "<span class='badge bg-primary'>Interview</span>";
                        } else {
                            echo "<span class='badge bg-secondary'>" . $app['status'] . "</span>";
                        }
                        ?>

                    </td>
                </tr>

                <tr>
                    <th>Submitted Date</th>
                    <td><?= $app['created_at'] ?></td>
                </tr>

            </table>

            <div class="mt-4">

                <a href="../dashboard.php"
                    class="btn btn-secondary">

                    Back

                </a>
            </div>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>