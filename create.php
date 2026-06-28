<?php
ob_start();
include '../../init.php';
$conn = dbConnect();
// Load roles
$sql="SELECT * FROM roles";
$stmt=$conn->prepare($sql);
$stmt->execute();
$roles=$stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_POST) {
    extract($_POST);


    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(first_name, last_name,dob,gender, mobile_no, whatsapp_no, addressline_1, addressline_2, addressline_3, email, password, role_id)
VALUES(:fname, :lname, :dob, :gender, :mobile_no, :whatsapp_no, :addressline_1, :addressline_2, :addressline_3, :email, :password, :role_id)";

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
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role_id', $role_id);

// $stmt->execute();

    header("Location: index.php");
}
?>

<h3>Create User</h3>

<form method="POST">
    <div class="mb-4">
        <label for="first_name">First Name</label>
        <input name="first_name" class="form-control mb-2" placeholder="First Name" required>
    </div>

    <div class="mb-4">
        <label for="last_name">Last Name</label>
        <input name="last_name" class="form-control mb-2" placeholder="Last Name" required>
    </div>

    <div class="mb-4">
        <label for="dob">Date of Birth</label>
        <input name="dob" type="date" class="form-control mb-2" placeholder="Date of Birth">
    </div>

    <div class="mb-4">
        <label for="gender">Gender</label>
        <input name="gender" type="text" class="form-control mb-2" placeholder="Gender">
    </div>
    
    <div class="mb-4">
        <label for="mobileno">Mobile No</label>
        <input name="mobileno" type="text" class="form-control mb-2" placeholder="Mobile No">
    </div>

    <div class="mb-4">
        <label for="whatsappno">WhatsApp No</label>
        <input name="whatsappno" type="text" class="form-control mb-2" placeholder="WhatsApp No">
    </div>

    <div class="mb-4">
        <label for="email">Email</label>
        <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
    </div>

    <div class="mb-4">
        <label for="addressline1">Address Line 1</label>
        <input name="addressline1" type="text" class="form-control mb-2" placeholder="Address Line 1">
    </div>

    <div class="mb-4">
        <label for="addressline2">Address Line 2</label>
        <input name="addressline2" type="text" class="form-control mb-2" placeholder="Address Line 2">
    </div>

    <div class="mb-4">
        <label for="addressline3">Address Line 3</label>
        <input name="addressline3" type="text" class="form-control mb-2" placeholder="Address Line 3">
    </div>

    <div class="mb-4">
        <label for="password">Password</label>
        <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
    </div>

    <select name="role_id" class="form-control mb-2">
        <?php foreach ($roles as $r): ?>
            <option value="<?= $r['id'] ?>"><?= $r['role_name'] ?></option>
        <?php endforeach; ?>
    </select>

    <button class="btn btn-success">Save</button>
</form>

<?php
$content = ob_get_clean();
include '../layout.php';
