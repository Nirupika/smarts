<?php
ob_start();
include '../../init.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role_id'] != 5) {
    header("Location: login.php");
    exit();
}

$conn = dbConnect();

$errors = [];

if ($_POST) {

    extract($_POST);

    $parent_id = $_SESSION['user_id'];

    // Calculate Age as at 2027-01-31
    if (!empty($student_dob)) {
        $dobDate = new DateTime($student_dob);
        $cutoffDate = new DateTime('2027-01-31');
        $age_as_at_2027 = $dobDate->diff($cutoffDate)->y . "/" . $dobDate->diff($cutoffDate)->m . "/" . $dobDate->diff($cutoffDate)->d;
    } else {
        $age_as_at_2027 = 0;
    }

    // ================= VALIDATIONS =================

    if (empty($category)) {
        $errors['category'] = "Category is required";
    }

    if (empty($year)) {
        $errors['year'] = "Academic year is required";
    }

    if (empty($student_full_name)) {
        $errors['student_full_name'] = "Student full name is required";
    }

    if (empty($student_initial_name)) {
        $errors['student_initial_name'] = "Name with initials is required";
    }

    if (empty($student_gender)) {
        $errors['student_gender'] = "Gender is required";
    }

    if (empty($student_dob)) {
        $errors['student_dob'] = "Date of birth is required";
    }
    if (!empty($student_dob)) {
        $dob = new DateTime($student_dob);
        $today = new DateTime();
        $age = $today->diff($dob);

        if ($age->y >= 6) {
            if ($age->m > 0 || $age->d > 0) {
                $errors['student_dob'] = "Student must be below 6 years old";
            }
        }
        if ($age->y < 5) {
            $errors['student_dob'] = "Student must be above 5 years old";
        }
        $age = $age->y . " years, " . $age->m . " months, " . $age->d . " days";
    }

    if (empty($applicant_full_name)) {
        $errors['applicant_full_name'] = "Applicant full name is required";
    }

    if (empty($applicant_nic)) {
        $errors['applicant_nic'] = "NIC number is required";
    }

    if (empty($mobile_no)) {
        $errors['mobile_no'] = "Mobile number is required";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email address";
    }

    //image upload
    $location_map = null;

    if (isset($_FILES['location_map']) && $_FILES['location_map']['error'] == 0) {

        $allowed = ['jpg', 'jpeg', 'png'];

        $file_name = $_FILES['location_map']['name'];
        $file_tmp  = $_FILES['location_map']['tmp_name'];
        $file_size = $_FILES['location_map']['size'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors['location_map'] = "Only JPG, JPEG and PNG files are allowed.";
        }

        if ($file_size > 5 * 1024 * 1024) {
            $errors['location_map'] = "File size must be less than 5MB.";
        }

        if (empty($errors)) {

            $new_file_name = uniqid('map_', true) . '.' . $ext;

            $upload_dir = "uploads/location_maps/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            move_uploaded_file(
                $file_tmp,
                $upload_dir . $new_file_name
            );

            $location_map = $new_file_name;
        }
    }

    // Birth Certificate Upload
    $birth_certificate_image = null;

    if (
        isset($_FILES['birth_certificate_image']) &&
        $_FILES['birth_certificate_image']['error'] == 0
    ) {

        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

        $file_name = $_FILES['birth_certificate_image']['name'];
        $file_tmp  = $_FILES['birth_certificate_image']['tmp_name'];
        $file_size = $_FILES['birth_certificate_image']['size'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors['birth_certificate_image']
                = "Only JPG, JPEG, PNG and PDF files are allowed.";
        }

        if ($file_size > 5 * 1024 * 1024) {
            $errors['birth_certificate_image']
                = "File size must be less than 5MB.";
        }

        if (empty($errors)) {

            $new_file_name =
                uniqid('birth_', true) . '.' . $ext;

            $upload_dir =
                "uploads/birth_certificates/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            move_uploaded_file(
                $file_tmp,
                $upload_dir . $new_file_name
            );

            $birth_certificate_image =
                $new_file_name;
        }
    }

    // GS Certificate Upload
    $gs_certificate_image = null;

    if (
        isset($_FILES['gs_certificate_image']) &&
        $_FILES['gs_certificate_image']['error'] == 0
    ) {

        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

        $file_name = $_FILES['gs_certificate_image']['name'];
        $file_tmp  = $_FILES['gs_certificate_image']['tmp_name'];
        $file_size = $_FILES['gs_certificate_image']['size'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors['gs_certificate_image']
                = "Only JPG, JPEG, PNG and PDF files are allowed.";
        }

        if ($file_size > 5 * 1024 * 1024) {
            $errors['gs_certificate_image']
                = "File size must be less than 5MB.";
        }

        if (empty($errors)) {

            $new_file_name =
                uniqid('GS_', true) . '.' . $ext;

            $upload_dir =
                "uploads/gs_certificates/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            move_uploaded_file(
                $file_tmp,
                $upload_dir . $new_file_name
            );

            $gs_certificate_image = $new_file_name;
        }
    }
    // ================= INSERT =================

    if (empty($errors)) {

        $sql = "INSERT INTO admission_applications (

            parent_id,
            category_id,
            year_id,

            student_full_name,
            student_initial_name,
            student_gender,
            student_religion,
            student_medium,
            student_dob,
            age,

            applicant_full_name,
            applicant_initial_name,
            applicant_gender,
            applicant_nic,
            permanent_address,
            mobile_no,
            whatsapp_no,
            email,
            administrative_district,
            ds_division,
            gs_division,

            pd_no_1,
            serial_no_1,
            name_1,

            pd_no_2,
            serial_no_2,
            name_2,

            pd_no_3,
            serial_no_3,
            name_3,

            pd_no_4,
            serial_no_4,
            name_4,

            pd_no_5,
            serial_no_5,
            name_5,

            nearby_school_1,
            nearby_school_2,
            nearby_school_3,
            location_map,
            birth_certificate_image,
            gs_certificate_image

        ) VALUES (

            :parent_id,
            :category,
            :year,

            :student_full_name,
            :student_initial_name,
            :student_gender,
            :student_religion,
            :student_medium,
            :student_dob,
            :age,

            :applicant_full_name,
            :applicant_initial_name,
            :applicant_gender,
            :applicant_nic,
            :permanent_address,
            :mobile_no,
            :whatsapp_no,
            :email,
            :administrative_district,
            :ds_division,
            :gs_division,

            :pd_no_1,
            :serial_no_1,
            :name_1,

            :pd_no_2,
            :serial_no_2,
            :name_2,

            :pd_no_3,
            :serial_no_3,
            :name_3,

            :pd_no_4,
            :serial_no_4,
            :name_4,

            :pd_no_5,
            :serial_no_5,
            :name_5,

            :nearby_school_1,
            :nearby_school_2,
            :nearby_school_3,
            :location_map,
            :birth_certificate_image,
            :gs_certificate_image
        )";

        $stmt = $conn->prepare($sql);

        $stmt->execute([

            ':parent_id' => $parent_id,
            ':category' => $category,
            ':year' => $year,

            ':student_full_name' => $student_full_name,
            ':student_initial_name' => $student_initial_name,
            ':student_gender' => $student_gender,
            ':student_religion' => $student_religion,
            ':student_medium' => $student_medium,
            ':student_dob' => $student_dob,
            ':age' => $age,

            ':applicant_full_name' => $applicant_full_name,
            ':applicant_initial_name' => $applicant_initial_name,
            ':applicant_gender' => $applicant_gender,
            ':applicant_nic' => $applicant_nic,
            ':permanent_address' => $permanent_address,
            ':mobile_no' => $mobile_no,
            ':whatsapp_no' => $whatsapp_no,
            ':email' => $email,
            ':administrative_district' => $administrative_district,
            ':ds_division' => $ds_division,
            ':gs_division' => $gs_division,

            ':pd_no_1' => $pd_no_1,
            ':serial_no_1' => $serial_no_1,
            ':name_1' => $name_1,

            ':pd_no_2' => $pd_no_2,
            ':serial_no_2' => $serial_no_2,
            ':name_2' => $name_2,

            ':pd_no_3' => $pd_no_3,
            ':serial_no_3' => $serial_no_3,
            ':name_3' => $name_3,

            ':pd_no_4' => $pd_no_4,
            ':serial_no_4' => $serial_no_4,
            ':name_4' => $name_4,

            ':pd_no_5' => $pd_no_5,
            ':serial_no_5' => $serial_no_5,
            ':name_5' => $name_5,

            ':nearby_school_1' => $nearby_school_1,
            ':nearby_school_2' => $nearby_school_2,
            ':nearby_school_3' => $nearby_school_3,
            ':location_map' => $location_map,
            ':birth_certificate_image' => $birth_certificate_image,
            ':gs_certificate_image' => $gs_certificate_image

        ]);

        $application_id = $conn->lastInsertId();
        $application_no = "APP-" . str_pad($application_id, 6, '0', STR_PAD_LEFT);

        $sql = "UPDATE admission_applications
                SET application_no=:application_no
                WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':application_no' => $application_no,
            ':id' => $application_id
        ]);
        header("Location:success.php?application_no=$application_no");
        exit();
    }
}
?>
<section class="contact-section section-padding">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <form method="post"
                    enctype="multipart/form-data"
                    novalidate
                    class="custom-form contact-form p-4 shadow rounded bg-light">

                    <h2 class="text-center mb-4">
                        Student Admission Application
                    </h2>

                    <!-- Category -->
                    <div class="row">

                        

                            <?php

                            $conn = dbConnect();
                            $sql = "SELECT * FROM admission_categories";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Admission Category
                                </label>
                                <select name="category"
                                    class="form-control">

                                    <option value="">
                                        Select Category
                                    </option>

                                    <?php foreach ($categories as $cat) { ?>

                                        <option value="<?= $cat['id'] ?>" <?= (@$category == $cat['id']) ? 'selected' : '' ?>>
                                            <?= $cat['category'] ?>
                                        </option>

                                    <?php } ?>
                                </select>

                                <small class="text-danger">
                                    <?= @$errors['year'] ?>
                                </small>

                            </div>

                            <?php

                            $conn = dbConnect();
                            $sql = "SELECT * FROM academic_year";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $year = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Academic Year
                                </label>
                                <select name="academic_year"
                                    class="form-control">

                                    <option value="">
                                        Select Year
                                    </option>

                                    <?php foreach ($year as $yr) { ?>

                                        <option value="<?= $yr['id'] ?>" <?= (@$year == $yr['id']) ? 'selected' : '' ?>>
                                            <?= $yr['year'] ?>
                                        </option>

                                    <?php } ?>
                                </select>

                                <small class="text-danger">
                                    <?= @$errors['academic_year'] ?>
                                </small>

                            </div>
                        </div>

                        <!-- Student Details -->

                        <h4 class="mb-3 text-primary border-bottom pb-2">
                            Student Details
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Full Name</label>
                                <input type="text"
                                    name="student_full_name"
                                    class="form-control"
                                    value="<?= @$student_full_name ?>">
                                <small class="text-danger">
                                    <?= @$errors['student_full_name'] ?>
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Name with Initials</label>
                                <input type="text"
                                    name="student_initial_name"
                                    class="form-control"
                                    value="<?= @$student_initial_name ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Gender</label>
                                <select name="student_gender"
                                    class="form-control">

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option value="Male" <?= (@$student_gender == 'Male') ? 'selected' : '' ?>>
                                        Male
                                    </option>

                                    <option value="Female" <?= (@$student_gender == 'Female') ? 'selected' : '' ?>>
                                        Female
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Religion</label>
                                <input type="text"
                                    name="student_religion"
                                    class="form-control"
                                    value="<?= @$student_religion ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Medium</label>
                                <select name="student_medium"
                                    class="form-control">

                                    <option value="">
                                        Select Medium
                                    </option>

                                    <option value="Sinhala" <?= (@$student_medium == 'Sinhala') ? 'selected' : '' ?>>
                                        Sinhala
                                    </option>

                                    <option value="Tamil" <?= (@$student_medium == 'Tamil') ? 'selected' : '' ?>>
                                        Tamil
                                    </option>


                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Date of Birth</label>
                                <input type="date"
                                    name="student_dob"
                                    class="form-control"
                                    min="2021-02-01" max="2022-01-31"


                                    value="<?= @$student_dob ?>">
                                <small class="text-danger">
                                    <?= @$errors['student_dob'] ?>
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Birth Certificate Image</label>

                                <input type="file"
                                    name="birth_certificate_image"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.pdf">

                                <small class="text-danger">
                                    <?= @$errors['birth_certificate_image'] ?>
                                </small>
                            </div>

                        </div>

                        <!-- Applicant Details -->

                        <h4 class="mb-3 mt-4 text-primary border-bottom pb-2">
                            Applicant Details
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Full Name</label>
                                <input type="text"
                                    name="applicant_full_name"
                                    class="form-control"
                                    value="<?= @$applicant_full_name ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Name with Initials</label>
                                <input type="text"
                                    name="applicant_initial_name"
                                    class="form-control"
                                    value="<?= @$applicant_initial_name ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Gender</label>
                                <select name="applicant_gender"
                                    class="form-control">

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option value="Male" <?= (@$applicant_gender == 'Male') ? 'selected' : '' ?>>
                                        Male
                                    </option>

                                    <option value="Female" <?= (@$applicant_gender == 'Female') ? 'selected' : '' ?>>
                                        Female
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>NIC Number</label>
                                <input type="text"
                                    name="applicant_nic"
                                    class="form-control"
                                    value="<?= @$applicant_nic ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Mobile Number</label>
                                <input type="text"
                                    name="mobile_no"
                                    class="form-control"
                                    value="<?= @$mobile_no ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Permanent Address</label>
                                <textarea name="permanent_address"
                                    class="form-control"
                                    rows="3"><?= @$permanent_address ?></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Whatsapp Number</label>
                                <input type="text"
                                    name="whatsapp_no"
                                    class="form-control"
                                    value="<?= @$whatsapp_no ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email Address</label>
                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= @$email ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Administrative District</label>

                                <?php

                                $conn = dbConnect();
                                $sql = "SELECT * FROM admission_districts";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>


                                <select name="administrative_district"
                                    class="form-control">

                                    <option value="">
                                        Select District
                                    </option>

                                    <?php foreach ($districts as $dist) { ?>

                                        <option value="<?= $dist['id'] ?>" <?= (@$administrative_district == $dist['id']) ? 'selected' : '' ?>>
                                            <?= $dist['districts'] ?>
                                        </option>

                                    <?php } ?>
                                </select>

                                <small class="text-danger">
                                    <?= @$errors['administrative_district'] ?>
                                </small>


                            </div>

                            <div class="col-md-6 mb-3">
                                <label>DS-Division</label>

                                <?php

                                $conn = dbConnect();
                                $sql = "SELECT * FROM admission_dsdivision";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $ds_divisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>


                                <select name="ds_division"
                                    class="form-control">

                                    <option value="">
                                        Select DS-Division
                                    </option>

                                    <?php foreach ($ds_divisions as $ddivision) { ?>

                                        <option value="<?= $ddivision['id'] ?>" <?= (@$ds_division == $ddivision['id']) ? 'selected' : '' ?>>
                                            <?= $ddivision['ds_division'] ?>
                                        </option>

                                    <?php } ?>
                                </select>

                                <small class="text-danger">
                                    <?= @$errors['ds_division'] ?>
                                </small>


                            </div>

                            <div class="col-md-6 mb-3">
                                <label>GS Division Name</label>

                                <?php

                                $conn = dbConnect();
                                $sql = "SELECT * FROM admission_gsdivision";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $gs_divisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>


                                <select name="gs_division"
                                    class="form-control">

                                    <option value="">
                                        Select GS Division
                                    </option>

                                    <?php foreach ($gs_divisions as $division) { ?>

                                        <option value="<?= $division['id'] ?>" <?= (@$gs_division == $division['id']) ? 'selected' : '' ?>>
                                            <?= $division['gs_division'] ?>
                                        </option>

                                    <?php } ?>
                                </select>

                                <small class="text-danger">
                                    <?= @$errors['gs_division'] ?>
                                </small>

                            </div>

                            <div class="col-md-6 mb-3">
                                <label> GS Certificate Image</label>

                                <input type="file"
                                    name="gs_certificate_image"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.pdf">

                                <small class="text-danger">
                                    <?= @$errors['gs_certificate_image'] ?>
                                </small>
                            </div>

                        </div>

                        <!-- Electoral Registration -->

                        <h4 class="mb-3 mt-4 text-primary border-bottom pb-2">
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
                                            <input type="text"
                                                name="pd_no_<?= $i ?>"
                                                class="form-control">
                                        </td>

                                        <td>
                                            <input type="text"
                                                name="serial_no_<?= $i ?>"
                                                class="form-control">
                                        </td>

                                        <td>
                                            <input type="text"
                                                name="name_<?= $i ?>"
                                                class="form-control">
                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                        <!-- Nearby Schools -->

                        <h4 class="mb-3 mt-4 text-primary border-bottom pb-2">
                            Other Schools Near the Place of Residence
                        </h4>

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <input type="text"
                                    name="nearby_school_1"
                                    class="form-control"
                                    placeholder="School 1" value="<?= @$nearby_school_1 ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <input type="text"
                                    name="nearby_school_2"
                                    class="form-control"
                                    placeholder="School 2" value="<?= @$nearby_school_2 ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <input type="text"
                                    name="nearby_school_3"
                                    class="form-control"
                                    placeholder="School 3" value="<?= @$nearby_school_3 ?>">
                            </div>

                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Location Map Image</label>

                            <input type="file"
                                name="location_map"
                                class="form-control"
                                accept=".jpg,.jpeg,.png">

                            <small class="text-danger">
                                <?= @$errors['location_map'] ?>
                            </small>
                        </div>
                        <!-- Submit Button -->

                        <div class="mt-4">

                            <button type="submit"
                                class="btn btn-primary w-100">

                                Submit Admission Application

                            </button>

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