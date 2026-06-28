<?php
ob_start();
include '../../init.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: view-parent.php");
    exit();
}

$conn = dbConnect();

$user_id = $_SESSION['user_id'];


$sql = "SELECT * FROM users WHERE id=:id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}

$errors = [];

if ($_POST) {

    extract($_POST);

    // Validation

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
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email address";
    }

    if (empty($errors)) {

        try {

            if (!empty($password)) {

                $hashedPassword =
                    password_hash($password, PASSWORD_DEFAULT);

                $sql = "UPDATE users SET

                        first_name=:first_name,
                        last_name=:last_name,
                        dob=:dob,
                        gender=:gender,
                        mobile_no=:mobile_no,
                        whatsapp_no=:whatsapp_no,
                        addressline_1=:addressline_1,
                        addressline_2=:addressline_2,
                        addressline_3=:addressline_3,
                        email=:email,
                        password=:password

                        WHERE id=:id";

                $stmt = $conn->prepare($sql);

                $stmt->execute([

                    ':first_name'=>$first_name,
                    ':last_name'=>$last_name,
                    ':dob'=>$dob,
                    ':gender'=>$gender,
                    ':mobile_no'=>$mobile_no,
                    ':whatsapp_no'=>$whatsapp_no,
                    ':addressline_1'=>$addressline_1,
                    ':addressline_2'=>$addressline_2,
                    ':addressline_3'=>$addressline_3,
                    ':email'=>$email,
                    ':password'=>$hashedPassword,
                    ':id'=>$user_id
                ]);

            } else {

                $sql = "UPDATE users SET

                        first_name=:first_name,
                        last_name=:last_name,
                        dob=:dob,
                        gender=:gender,
                        mobile_no=:mobile_no,
                        whatsapp_no=:whatsapp_no,
                        addressline_1=:addressline_1,
                        addressline_2=:addressline_2,
                        addressline_3=:addressline_3,
                        email=:email

                        WHERE id=:id";

                $stmt = $conn->prepare($sql);

                $stmt->execute([

                    ':first_name'=>$first_name,
                    ':last_name'=>$last_name,
                    ':dob'=>$dob,
                    ':gender'=>$gender,
                    ':mobile_no'=>$mobile_no,
                    ':whatsapp_no'=>$whatsapp_no,
                    ':addressline_1'=>$addressline_1,
                    ':addressline_2'=>$addressline_2,
                    ':addressline_3'=>$addressline_3,
                    ':email'=>$email,
                    ':id'=>$user_id
                ]);
            }

            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;

            header("Location: view-parent.php?updated=1");
            exit();

        } catch (Exception $e) {

            $errors['database'] = $e->getMessage();
        }
    }
}
?>

<section class="contact-section section-padding">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<form method="post"
      class="custom-form contact-form p-4 shadow rounded bg-light">

<h2 class="text-center mb-4">
    Edit My Profile
</h2>

<div class="row">

<div class="col-md-6 mb-3">
<label>First Name</label>
<input type="text"
       name="first_name"
       class="form-control"
       value="<?= $user['first_name'] ?>">
<small class="text-danger">
<?= @$errors['first_name'] ?>
</small>
</div>

<div class="col-md-6 mb-3">
<label>Last Name</label>
<input type="text"
       name="last_name"
       class="form-control"
       value="<?= $user['last_name'] ?>">
<small class="text-danger">
<?= @$errors['last_name'] ?>
</small>
</div>

<div class="col-md-6 mb-3">
<label>Date of Birth</label>
<input type="date"
       name="dob"
       class="form-control"
       value="<?= $user['dob'] ?>">
</div>

<div class="col-md-6 mb-3">
<label>Gender</label>

<select name="gender" class="form-control">

<option value="Male"
<?= ($user['gender']=='Male')?'selected':'' ?>>
Male
</option>

<option value="Female"
<?= ($user['gender']=='Female')?'selected':'' ?>>
Female
</option>

</select>

</div>

<div class="col-md-6 mb-3">
<label>Mobile Number</label>
<input type="text"
       name="mobile_no"
       class="form-control"
       value="<?= $user['mobile_no'] ?>">
</div>

<div class="col-md-6 mb-3">
<label>WhatsApp Number</label>
<input type="text"
       name="whatsapp_no"
       class="form-control"
       value="<?= $user['whatsapp_no'] ?>">
</div>

<div class="col-md-12 mb-3">
<label>Address Line 1</label>
<input type="text"
       name="addressline_1"
       class="form-control"
       value="<?= $user['addressline_1'] ?>">
</div>

<div class="col-md-12 mb-3">
<label>Address Line 2</label>
<input type="text"
       name="addressline_2"
       class="form-control"
       value="<?= $user['addressline_2'] ?>">
</div>

<div class="col-md-12 mb-3">
<label>Address Line 3</label>
<input type="text"
       name="addressline_3"
       class="form-control"
       value="<?= $user['addressline_3'] ?>">
</div>

<div class="col-md-12 mb-3">
<label>Email Address</label>
<input type="email"
       name="email"
       class="form-control"
       value="<?= $user['email'] ?>">
</div>

<div class="col-md-12 mb-4">
<label>
New Password
(Skip if you don't want to change it)
</label>

<input type="password"
       name="password"
       class="form-control">
</div>

<div class="col-md-12">

<button type="submit"
        class="btn btn-primary w-100">

Update Profile

</button>

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