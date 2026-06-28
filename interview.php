<?php
ob_start();

include '../../init.php';

$conn = dbConnect();

$application_id = $_GET['id'] ?? 0;

/* Load Application Details */
$sql = "SELECT *
        FROM admission_applications
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $application_id]);

$application = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$application) {
    die("Application not found");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $interview_date = trim($_POST['interview_date']);
    $interview_time = trim($_POST['interview_time']);
    $venue          = trim($_POST['venue']);
    $remarks        = trim($_POST['remarks']);

    if (empty($interview_date)) {
        $errors['interview_date'] = "Interview date is required";
    }

    if (empty($interview_time)) {
        $errors['interview_time'] = "Interview time is required";
    }

    if (empty($errors)) {

        /* Save Interview */

        $sql = "INSERT INTO admission_interviews
                (
                    application_id,
                    interview_date,
                    interview_time,
                    venue,
                    remarks
                )
                VALUES
                (
                    :application_id,
                    :interview_date,
                    :interview_time,
                    :venue,
                    :remarks
                )";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':application_id' => $application_id,
            ':interview_date' => $interview_date,
            ':interview_time' => $interview_time,
            ':venue' => $venue,
            ':remarks' => $remarks
        ]);

        /* Update Application Status */

        $sql = "UPDATE admission_applications
                SET status='Interview'
                WHERE id=:id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':id' => $application_id
        ]);
        $sql = "SELECT *
            FROM admission_applications
            WHERE status='Interview'
            ORDER BY created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rejected_applications = $stmt->fetchAll(PDO::FETCH_ASSOC);


        header("Location:selection-dashboard.php");
        exit;

       
    }
}
?>

<h3 class="mb-3">Schedule Interview</h3>



<div class="card-body">

    <div class="alert alert-info">

        <strong>Student Name:</strong>
        <?= htmlspecialchars($application['student_full_name']) ?>

        <br>

        <strong>Applicant Name:</strong>
        <?= htmlspecialchars($application['applicant_full_name']) ?>

    </div>

    <form method="post">

        <div class="mb-3">

            <label class="form-label">
                Interview Date
            </label>

            <input type="date"
                name="interview_date"
                class="form-control"
                value="<?= $_POST['interview_date'] ?? '' ?>">

            <small class="text-danger">
                <?= $errors['interview_date'] ?? '' ?>
            </small>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Interview Time
            </label>

            <input type="time"
                name="interview_time"
                class="form-control"
                value="<?= $_POST['interview_time'] ?? '' ?>">

            <small class="text-danger">
                <?= $errors['interview_time'] ?? '' ?>
            </small>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Venue
            </label>

            <input type="text"
                name="venue"
                class="form-control"
                value="<?= $_POST['venue'] ?? '' ?>">

        </div>

        <div class="mb-3">

            <label class="form-label">
                Remarks
            </label>

            <textarea name="remarks"
                class="form-control"
                rows="4"><?= $_POST['remarks'] ?? '' ?></textarea>

        </div>

        <button type="submit"
            class="btn btn-success">

            Schedule Interview

        </button>

        <a href="../applications/index.php"
            class="btn btn-secondary">

            Cancel

        </a>

    </form>



</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>