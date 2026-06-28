<?php
ob_start();
include '../../init.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$conn = dbConnect();

$user_id = $_SESSION['user_id'];

$sql = "SELECT *
        FROM users
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}
?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">
                My Profile
            </h3>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>First Name</strong><br>
                    <?= $user['first_name'] ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Last Name</strong><br>
                    <?= $user['last_name'] ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Date of Birth</strong><br>
                    <?= $user['dob'] ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Gender</strong><br>
                    <?= $user['gender'] ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Mobile Number</strong><br>
                    <?= $user['mobile_no'] ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>WhatsApp Number</strong><br>
                    <?= $user['whatsapp_no'] ?>
                </div>

                <div class="col-md-12 mb-3">
                    <strong>Address</strong><br>
                    <?= $user['addressline_1'] ?><br>
                    <?= $user['addressline_2'] ?><br>
                    <?= $user['addressline_3'] ?>
                </div>

                <div class="col-md-12 mb-3">
                    <strong>Email Address</strong><br>
                    <?= $user['email'] ?>
                </div>

                <div class="col-md-12 mb-3">
                    <strong>Account Created On</strong><br>
                    <?= $user['created_at'] ?>
                </div>

            </div>

            <div class="mt-3">

                <a href="edit-parent.php"
                   class="btn btn-primary">
                    Edit Profile
                </a>

                <a href="../dashboard.php"
                   class="btn btn-secondary">
                    Back to Dashboard
                </a>

            </div>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>