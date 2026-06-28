<?php
ob_start();
include '../../init.php';
?>

<section class="contact-section section-padding">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card shadow border-0 rounded-4">

                    <div class="card-body text-center p-5">

                        <!-- Success Icon -->
                        <div class="mb-4">

                            <i class="bi bi-check-circle-fill text-success"
                               style="font-size:80px;"></i>

                        </div>

                        <!-- Title -->
                        <h2 class="mb-3 text-success">
                            Registration Successful
                        </h2>

                        <!-- Message -->
                        <p class="mb-4 fs-5">

                            Your parent account has been created successfully.

                            <br><br>

                            You can now continue with the student admission
                            application process.

                        </p>

                       <!-- Buttons -->
                        <div class="d-grid gap-3">

                            <a href="application-form.php"
                               class="btn btn-primary btn-lg">

                                Continue Student Admission

                            </a>

                            <a href="../login.php"
                               class="btn btn-outline-secondary btn-lg">

                                Parent Login

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php
$content = ob_get_clean();
include '../layout.php';
?>