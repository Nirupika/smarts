
<?php
ob_start();
include '../../init.php';
if (!hasPermission('users/edit.php')) {
    die("Access Denied");
}

$conn = dbConnect();

// 🔹 Get user ID
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid User ID");
}

// 🔹 Load user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}

// 🔹 Load roles
$roles = $conn->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);

// 🔹 UPDATE LOGIC
if ($_POST) {

    extract($_POST);

    try {

        // 🔹 If password entered → hash it
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET
                        first_name = :fname,
                        last_name = :lname,
                        dob = :dob,
                        gender = :gender,
                        mobile_no = :mobile_no,
                        whatsapp_no = :whatsapp_no,
                        addressline_1 = :addressline_1,
                        addressline_2 = :addressline_2,
                        addressline_3 = :addressline_3,
                        email = :email,
                        password = :password,
                        role_id = :role_id
                    WHERE id = :id";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':fname' => $first_name,
                ':lname' => $last_name,
                ':dob' => $dob,
                ':gender' => $gender,
                ':mobile_no' => $mobile_no,
                ':whatsapp_no' => $whatsapp_no,
                ':addressline_1' => $addressline_1,
                ':addressline_2' => $addressline_2,
                ':addressline_3' => $addressline_3,
                ':email' => $email,
                ':password' => $password,
                ':role_id' => $role_id,
                ':id' => $id
            ]);
        } else {
            // 🔹 No password change
            $sql = "UPDATE users SET
                        first_name = :fname,
                        last_name = :lname,
                        dob = :dob,
                        gender = :gender,
                        mobile_no = :mobile_no,
                        whatsapp_no = :whatsapp_no,
                        addressline_1 = :addressline_1,
                        addressline_2 = :addressline_2,
                        addressline_3 = :addressline_3,
                        email = :email,
                        role_id = :role_id
                    WHERE id = :id";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':fname' => $first_name,
                ':lname' => $last_name,
                ':dob' => $dob,
                ':gender' => $gender,
                ':mobile_no' => $mobile_no,
                ':whatsapp_no' => $whatsapp_no,
                ':addressline_1' => $addressline_1,
                ':addressline_2' => $addressline_2,
                ':addressline_3' => $addressline_3,
                ':email' => $email,
                ':role_id' => $role_id,
                ':id' => $id
            ]);
        }

        header("Location: index.php?updated=1");
        exit;
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    }
}
?>

<h3>Edit User</h3>

<form method="POST">

    <!-- First Name -->
    <div class="mb-2">
        <input name="first_name" class="form-control"
            value="<?= $user['first_name'] ?>" required>
    </div>

    <!-- Last Name -->
    <div class="mb-2">
        <input name="last_name" class="form-control"
            value="<?= $user['last_name'] ?>" required>
    </div>

    <!-- Date of Birth -->
    <div class="mb-2">
        <input name="dob" type="date" class="form-control"
            value="<?= $user['dob'] ?>"
    </div>

    <!-- Gender -->
    <div class="mb-2">
        <input name="gender" type="text" class="form-control"
            value="<?= $user['gender'] ?>"
    </div>      

     <!-- Mobile No -->
     <div class="mb-2">
        <input name="mobile_no" type="text" class="form-control"
            value="<?= $user['mobile_no'] ?>"
     </div>     

        <!-- Whatsapp No -->    
        <div class="mb-2">
            <input name="whatsapp_no" type="text" class="form-control"
                value="<?= $user['whatsapp_no'] ?>"
        </div>  

        <!-- Address -->  
        <div class="mb-2">
            <input name="addressline_1" type="text" class="form-control mb-1"
                value="<?= $user['addressline_1'] ?>" placeholder="Address Line 1">
            <input name="addressline_2" type="text" class="form-control mb-1"
                value="<?= $user['addressline_2'] ?>" placeholder="Address Line 2">
            <input name="addressline_3" type="text" class="form-control"
                value="<?= $user['addressline_3'] ?>" placeholder="Address Line 3">
        </div>

    <!-- Email -->
    <div class="mb-2">
        <input name="email" type="email" class="form-control"
            value="<?= $user['email'] ?>" required>
    </div>

    <!-- Password -->
    <div class="mb-2">
        <input name="password" type="password" class="form-control"
            placeholder="Leave blank to keep current password">
    </div>

    <!-- Role -->
<div class="mb-2">
    <select name="role_id" class="form-control mb-2">
        <?php foreach ($roles as $r): ?>
            <option value="<?= $r['id'] ?>"><?= $r['role_name'] ?></option>
        <?php endforeach; ?>
    </select>     
</div>

    <button class="btn btn-primary">Update User</button>

</form>

<?php
$content = ob_get_clean();
include '../layout.php';
?>

