<?php
ob_start();
include '../../init.php';

$conn = dbConnect();

$errors = [];

if ($_POST) {

    // Sanitize Inputs
    $first_name       = trim($_POST['first_name']);
    $last_name        = trim($_POST['last_name']);
    $dob              = $_POST['dob'];
    $gender           = $_POST['gender'];
    $mobile_no        = trim($_POST['mobile_no']);
    $whatsapp_no      = trim($_POST['whatsapp_no']);
    $addressline_1    = trim($_POST['addressline_1']);
    $addressline_2    = trim($_POST['addressline_2']);
    $addressline_3    = trim($_POST['addressline_3']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Fixed Parent Role ID
    $role_id = 5;

    // ================= VALIDATIONS =================

    if (empty($first_name)) {
        $errors['first_name'] = "First name is required";
    }

    if (empty($last_name)) {
        $errors['last_name'] = "Last name is required";
    }

    if (empty($dob)) {
        $errors['dob'] = "Date of birth is required";
    }

    if (empty($gender)) {
        $errors['gender'] = "Gender is required";
    }

    if (empty($mobile_no)) {
        $errors['mobile_no'] = "Mobile number is required";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile_no)) {
        $errors['mobile_no'] = "Enter valid 10 digit mobile number";
    }

    if (!empty($whatsapp_no) && !preg_match('/^[0-9]{10}$/', $whatsapp_no)) {
        $errors['whatsapp_no'] = "Enter valid 10 digit WhatsApp number";
    }

    if (empty($addressline_1)) {
        $errors['addressline_1'] = "Address Line 1 is required";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must contain at least 6 characters";
    }

    if ($password != $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    // Check Duplicate Email
    $sqlCheck = "SELECT * FROM users WHERE email = :email";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':email', $email);
    $stmtCheck->execute();

    if ($stmtCheck->rowCount() > 0) {
        $errors['email'] = "Email already exists";
    }

    // ================= INSERT DATA =================

    if (empty($errors)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users
        (
            first_name,
            last_name,
            dob,
            gender,
            mobile_no,
            whatsapp_no,
            addressline_1,
            addressline_2,
            addressline_3,
            email,
            password,
            role_id
        )

        VALUES
        (
            :fname,
            :lname,
            :dob,
            :gender,
            :mobile_no,
            :whatsapp_no,
            :addressline_1,
            :addressline_2,
            :addressline_3,
            :email,
            :password,
            :role_id
        )";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':fname', $first_name);
        $stmt->bindParam(':lname', $last_name);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':mobile_no', $mobile_no);
        $stmt->bindParam(':whatsapp_no', $whatsapp_no);
        $stmt->bindParam(':addressline_1', $addressline_1);
        $stmt->bindParam(':addressline_2', $addressline_2);
        $stmt->bindParam(':addressline_3', $addressline_3);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role_id', $role_id);

        if ($stmt->execute()) {

            header("Location: register-success.php");
            exit();
        } else {
            $errors['database'] = "Registration failed";
        }
    }
}
?>

<section class="contact-section section-padding">
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <form novalidate method="post" class="custom-form contact-form p-4 shadow rounded bg-light">

                    <h2 class="text-center mb-4">
                        Parent Registration
                    </h2>

                    <?php if (!empty($errors['database'])) { ?>
                        <div class="alert alert-danger">
                            <?= $errors['database'] ?>
                        </div>
                    <?php } ?>

                    <div class="row">

                        <!-- First Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>

                            <input type="text"
                                name="first_name"
                                class="form-control"
                                value="<?= @$first_name ?>">

                            <small class="text-danger">
                                <?= @$errors['first_name'] ?>
                            </small>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>

                            <input type="text"
                                name="last_name"
                                class="form-control"
                                value="<?= @$last_name ?>">

                            <small class="text-danger">
                                <?= @$errors['last_name'] ?>
                            </small>
                        </div>

                        <!-- DOB -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>

                            <input type="date"
                                name="dob"
                                class="form-control"
                                value="<?= @$dob ?>">

                            <small class="text-danger">
                                <?= @$errors['dob'] ?>
                            </small>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>

                            <select name="gender" class="form-control">

                                <option value="">Select Gender</option>

                                <option value="Male"
                                    <?= (@$gender == 'Male') ? 'selected' : '' ?>>
                                    Male
                                </option>

                                <option value="Female"
                                    <?= (@$gender == 'Female') ? 'selected' : '' ?>>
                                    Female
                                </option>

                            </select>

                            <small class="text-danger">
                                <?= @$errors['gender'] ?>
                            </small>
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number</label>

                            <input type="text"
                                name="mobile_no"
                                class="form-control"
                                value="<?= @$mobile_no ?>">

                            <small class="text-danger">
                                <?= @$errors['mobile_no'] ?>
                            </small>
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">WhatsApp Number</label>

                            <input type="text"
                                name="whatsapp_no"
                                class="form-control"
                                value="<?= @$whatsapp_no ?>">

                            <small class="text-danger">
                                <?= @$errors['whatsapp_no'] ?>
                            </small>
                        </div>

                        <!-- Address Line 1 -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address Line 1</label>

                            <input type="text"
                                name="addressline_1"
                                class="form-control"
                                value="<?= @$addressline_1 ?>">

                            <small class="text-danger">
                                <?= @$errors['addressline_1'] ?>
                            </small>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address Line 2</label>

                            <input type="text"
                                name="addressline_2"
                                class="form-control"
                                value="<?= @$addressline_2 ?>">
                        </div>

                        <!-- Address Line 3 -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address Line 3</label>

                            <input type="text"
                                name="addressline_3"
                                class="form-control"
                                value="<?= @$addressline_3 ?>">
                        </div>

                        <!-- Email -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Email Address</label>

                            <input type="email"
                                name="email"
                                class="form-control"
                                value="<?= @$email ?>">

                            <small class="text-danger">
                                <?= @$errors['email'] ?>
                            </small>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>

                            <input type="password"
                                name="password"
                                class="form-control">

                            <small class="text-danger">
                                <?= @$errors['password'] ?>
                            </small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                Confirm Password
                            </label>

                            <input type="password"
                                name="confirm_password"
                                class="form-control">

                            <small class="text-danger">
                                <?= @$errors['confirm_password'] ?>
                            </small>
                        </div>

                        <!-- Submit Button -->
            

                        <div class="text-center mt-3">

                        <button type="submit"
                            

                        <a href="register-success.php"
                               class="btn btn-primary btn-lg">

                              Create Parent Account  

                            </a>

                            
                        

                        </button>
                        </div>
                                

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>
</section>

<?php
$content = ob_get_clean();
include '../layout.php';
?>