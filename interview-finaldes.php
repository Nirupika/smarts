<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$application_id = $_GET['id'] ?? 0;

/* Get interview details */

$sql = "SELECT
            ai.id AS interview_id,
            aa.application_no,
            aa.student_full_name,
            aa.applicant_full_name
        FROM admission_interviews ai
        INNER JOIN admission_applications aa
            ON ai.application_id = aa.id
        WHERE aa.id = :application_id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':application_id' => $application_id
]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$student){
    die("Invalid Application");
}

/* Save or Update Final Decision */

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $interview_status = $_POST['interview_status'];
    $remarks = $_POST['remarks'];

    // Check whether a decision already exists
    $check = $conn->prepare("
        SELECT id
        FROM admission_interview_status
        WHERE application_id = :application_id
    ");

    $check->execute([
        ':application_id' => $application_id
    ]);

    if($check->fetch()){

        // Update existing interview decision
        $sql = "UPDATE admission_interview_status
                SET interview_status = :interview_status,
                    remarks = :remarks,
                    selected_by = :selected_by,
                    selected_date = NOW()
                WHERE application_id = :application_id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':interview_status' => $interview_status,
            ':remarks' => $remarks,
            ':selected_by' => $_SESSION['user_id'],
            ':application_id' => $application_id
        ]);

    }else{

        // Insert new interview decision
        $sql = "INSERT INTO admission_interview_status
                (
                    application_id,
                    interview_id,
                    interview_status,
                    remarks,
                    selected_by,
                    selected_date
                )
                VALUES
                (
                    :application_id,
                    :interview_id,
                    :interview_status,
                    :remarks,
                    :selected_by,
                    NOW()
                )";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':application_id' => $application_id,
            ':interview_id' => $student['interview_id'],
            ':interview_status' => $interview_status,
            ':remarks' => $remarks,
            ':selected_by' => $_SESSION['user_id']
        ]);
    }

    // Update application status
    $sql = "UPDATE admission_applications
            SET status = :status
            WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':status' => $interview_status,
        ':id' => $application_id
    ]);

    header("Location: selectioned-students.php");
    exit;
}
?>

<h3 class="mb-3">
    Final Interview Decision
</h3>

<div class="card shadow">

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="200">Application No</th>
                <td><?= $student['application_no'] ?></td>
            </tr>

            <tr>
                <th>Student Name</th>
                <td><?= $student['student_full_name'] ?></td>
            </tr>

            <tr>
                <th>Applicant Name</th>
                <td><?= $student['applicant_full_name'] ?></td>
            </tr>

        </table>

        <form method="post">

            <div class="mb-3">

                <label class="form-label">
                    Final Decision
                </label>

                <select name="interview_status"
                        class="form-control"
                        required>

                    <option value="">
                        -- Select Decision --
                    </option>

                    <option value="Accepted">
                        Selected
                    </option>

                    <option value="Waiting List">
                        Waiting List
                    </option>

                    <option value="Rejected">
                        Rejected
                    </option>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Remarks
                </label>

                <textarea name="remarks"
                          class="form-control"
                          rows="4"></textarea>

            </div>

            <button type="submit"
                    class="btn btn-success">

                Save Decision

            </button>

            <a href="student-selection.php"
               class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>