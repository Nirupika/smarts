<?php
ob_start();
include '../init.php';

// ================= LOGIN VALIDATION =================

if(!isset($_SESSION['logged_in'])){

    header("Location: login.php");
    exit();

}

if($_SESSION['role_id'] != 5){

    header("Location: login.php");
    exit();

}
?>

<section class="section-padding">

    <div class="container">

        <!-- Welcome Section -->
        <div class="row mb-5">

            <div class="col-lg-12">

                <div class="card border-0 shadow rounded-4 bg-primary text-white">

                    <div class="card-body p-5">

                        <h2 class="mb-3">

                            Welcome,
                            <?= $_SESSION['first_name']; ?>

                        </h2>

                        <p class="mb-0 fs-5">

                            Welcome to the Parent Portal of
                            Sujatha Balika Maha Vidyalaya.

                            <br>

                            You can now manage student admissions,
                            applications and parent activities using this dashboard.

                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- Dashboard Cards -->
        <div class="row">

            <!-- Application Submission -->
            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="card-body text-center p-4">

                        <div class="mb-4">

                            <i class="bi bi-file-earmark-text-fill text-primary"
                               style="font-size:70px;"></i>

                        </div>

                        <h4 class="mb-3">

                           
                        Admission Application Submission 

                        </h4>

                        <p class="mb-4">

                            Submit a new student admission application
                            to the school.

                        </p>

                        <a href="parent/application-form.php"
                           class="btn btn-primary w-100">

                            Submit Application Form

                        </a>
                        
                    </div>

                </div>

            </div>

            <!-- Student Management -->
            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="card-body text-center p-4">

                        <div class="mb-4">

                            <i class="bi bi-mortarboard-fill text-success"
                               style="font-size:70px;"></i>

                        </div>

                        <h4 class="mb-3">

                            Student Management

                        </h4>

                        <p class="mb-4">

                            View student details and manage
                            student information.

                        </p>

                        <a href="students.php"
                           class="btn btn-success w-100">

                            Manage Students

                        </a>

                    </div>

                </div>

            </div>

            <!-- Application Status -->
            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="card-body text-center p-4">

                        <div class="mb-4">

                            <i class="bi bi-clock-history text-warning"
                               style="font-size:70px;"></i>

                        </div>

                        <h4 class="mb-3">

                            Application Status

                        </h4>

                        <p class="mb-4">

                            Check the status of submitted
                            admission applications.

                        </p>
                       
                        <a href="parent/status.php"
                           class="btn btn-warning w-100 text-white">

                            View Status

                        </a>

                    </div>

                </div>

            </div>

            <!-- Parent Profile -->
            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="card-body text-center p-4">

                        <div class="mb-4">

                            <i class="bi bi-person-circle text-info"
                               style="font-size:70px;"></i>

                        </div>

                        <h4 class="mb-3">

                            Parent Profile

                        </h4>

                        <p class="mb-4">

                            View and update your parent account details.

                        </p>

                        <a href="parent/view-parent.php"
                           class="btn btn-info w-100 text-white">

                            View Profile

                        </a>

                    </div>

                </div>

            </div>

            <!-- Notifications -->
            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="card-body text-center p-4">

                        <div class="mb-4">

                            <i class="bi bi-bell-fill text-danger"
                               style="font-size:70px;"></i>

                        </div>

                        <h4 class="mb-3">

                            Notifications

                        </h4>

                        <p class="mb-4">

                            View important school announcements
                            and notifications.

                        </p>

                        <a href="parent/interview-results.php"
                           class="btn btn-danger w-100">

                            View Notifications

                        </a>

                    </div>

                </div>

            </div>

            <!-- Logout -->
            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="card-body text-center p-4">

                        <div class="mb-4">

                            <i class="bi bi-box-arrow-right text-dark"
                               style="font-size:70px;"></i>

                        </div>

                        <h4 class="mb-3">

                            Logout

                        </h4>

                        <p class="mb-4">

                            Securely logout from the parent portal.

                        </p>

                        <a href="logout.php"
                           class="btn btn-dark w-100">

                            Logout

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php
$content = ob_get_clean();
include 'layout.php';
?>