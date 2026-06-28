<?php
ob_start();
include '../../init.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role_id'] != 3 && $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

$conn = dbConnect();

/* Statistics */
$totalApplications = $conn->query("
    SELECT COUNT(*) FROM admission_applications
")->fetchColumn();

$pendingApplications = $conn->query("
    SELECT COUNT(*) FROM admission_applications
    WHERE status='Pending' OR status IS NULL
")->fetchColumn();

$acceptedApplications = $conn->query("
    SELECT COUNT(*) FROM admission_applications
    WHERE status='Approved'
")->fetchColumn();

$rejectedApplications = $conn->query("
    SELECT COUNT(*) FROM admission_applications
    WHERE status='Rejected'
")->fetchColumn();

?>

<section class="section-padding">
    <div class="container">

        <!-- Welcome Section -->
        <div class="row mb-4">

            <div class="col-12">

                <div class="card shadow border-0">

                    <div class="card-body">

                        <h2 class="mb-2">
                            Welcome,
                            <?= $_SESSION['first_name']; ?>
                        </h2>

                        <p class="text-muted mb-0">
                            Selection Committee Dashboard
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- Statistics -->
        <div class="row mb-4">

            <div class="col-md-3 mb-3">

                <div class="card text-center shadow border-0">

                    <div class="card-body">

                        <h5>Total Applications</h5>

                        <h2 class="text-primary">
                            <?= $totalApplications ?>
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="card text-center shadow border-0">

                    <div class="card-body">

                        <h5>Pending</h5>

                        <h2 class="text-warning">
                            <?= $pendingApplications ?>
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="card text-center shadow border-0">

                    <div class="card-body">

                        <h5>Approved</h5>

                        <h2 class="text-success">
                            <?= $acceptedApplications ?>
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-3">

                <div class="card text-center shadow border-0">

                    <div class="card-body">

                        <h5>Rejected</h5>

                        <h2 class="text-danger">
                            <?= $rejectedApplications ?>
                        </h2>

                    </div>

                </div>

            </div>

        </div>

        <!-- Main Functions -->
        <div class="row">

            <!-- Applications -->
            <div class="col-md-3 mb-4">

                <div class="card h-100 shadow">

                    <div class="card-body text-center">

                        <h4>Applications</h4>

                        <p>
                            View and review submitted admission applications.
                        </p>

                        <a href="view-applicationn.php"
                            class="btn btn-light">

                            View Applications

                        </a>

                    </div>

                </div>

            </div>

            <!-- Accepted Students -->
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow">

                    <div class="card-body text-center">

                        <h5>Accepted Students</h5>

                        <p>
                            View the list of students who have been approved for admission.
                        </p>

                        <a href="approved-list.php"
                            class="btn btn-primary">

                            View List

                        </a>

                    </div>

                </div>

            </div>

            <!-- Interview Management -->
            <div class="col-md-3 mb-4">

                <div class="card h-100 shadow">

                    <div class="card-body text-center">

                        <h4>Interviews</h4>

                        <p>
                            Schedule and manage admission interviews.
                        </p>

                        <a href="interview-list.php"
                            class="btn btn-info">

                            Manage Interviews

                        </a>

                    </div>

                </div>

            </div>

            <!-- Messages -->
            <div class="col-md-3 mb-4">

                <div class="card h-100 shadow">

                    <div class="card-body text-center">

                        <h4>Messages</h4>

                        <p>
                            Send notifications and messages to parents.
                        </p>

                        <a href="messages/index.php"
                            class="btn btn-secondary">

                            Open Messages

                        </a>

                    </div>

                </div>

            </div>

            <!-- Student Selection -->
            <div class="col-md-3 mb-4">

                <div class="card h-100 shadow">

                    <div class="card-body text-center">

                        <h4>Student Selection</h4>

                        <p>
                            Manage the student selection process.
                        </p>

                        <a href="interview-dashboard.php"
                            class="btn btn-primary">

                            Student Selection

                        </a>

                    </div>
                  
                
                   
                </div>

            </div>



            <!-- Additional Functions -->


            <!-- Rejected Students -->
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow">
                
                    <div class="card-body text-center">

                        <h5>Rejected Students</h5>

                        <p>
                            View the list of students whose applications have been rejected.
                        </p>

                        <a href="reject-list.php"
                            class="btn btn-danger">

                            View List

                        </a>

                    </div>

                </div>
            </div>
            <!-- Waiting List Students -->



            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow">
                    <div class="card-body text-center">
                        <h5>Waiting List Students</h5>

                        <p>
                            View the list of students on the waiting list.
                        </p>
                        `
                        <a href="waiting-students.php"
                            class="btn btn-warning">

                            View List

                        </a>

                    </div>
                </div>
            </div>



            <!-- Reports -->
            <div class="col-md-3 mb-4">

                <div class="card h-100 shadow">

                    <div class="card-body text-center">

                        <h5>Reports</h5>

                        <p>
                            Generate various reports related to the student selection process.
                        </p>

                        <a href="reports.php"
                            class="btn btn-dark">

                            Generate Reports

                        </a>

                    </div>


                </div>

            </div>

        </div>


        <?php
        $content = ob_get_clean();
        include '../layout.php';
        ?>