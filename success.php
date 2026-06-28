<?php
ob_start();
include '../../init.php';

$application_no = $_GET['application_no'] ?? '';
?>

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-body text-center">

            <div class="alert alert-success">
                <h2>✓ Application Submitted Successfully</h2>
            </div>

            <h4 class="mt-4">Your Application Number</h4>

            <h2 class="text-primary">
                <?php echo htmlspecialchars($application_no); ?>
            </h2>

            <hr>

            <p>
                Your application has been successfully submitted to the school admission system.
            </p>

            <p>
                Please keep your Application Number for future reference.
            </p>

            <p>
                You may be required to present this number during document verification and admission interviews.
            </p>

            <div class="mt-4">

                <button onclick="window.print();" class="btn btn-success">
                    Print Application Number
                </button>

                <a href="../../index.php" class="btn btn-primary">
                    Back to Home
                </a>

            </div>

        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include '../layout.php';
?>