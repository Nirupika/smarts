<?php
ob_start();
include '../../init.php';
$conn = dbConnect();

$sql = "SELECT u.*, r.role_name
FROM users u
JOIN roles r ON u.role_id = r.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h3>Users</h3>
 

<a href="create.php" class="btn btn-primary mb-2">Add User</a>


<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Gender</th>
        <th>Modile No</th>
        <th>Whatsapp No</th>
        <th>Address</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>


    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['first_name'] . ' ' . $u['last_name'] ?></td>
            <td><?= $u['dob'] ?></td>
            <td><?= $u['gender'] ?></td>
            <td><?= $u['mobile_no'] ?></td>
            <td><?= $u['whatsapp_no'] ?></td>
            <td><?= $u['addressline_1'] . ', ' . $u['addressline_2'] . ', ' . $u['addressline_3'] ?></td>
            <td><?= $u['email'] ?></td>
            <td><?= $u['role_name'] ?></td>
            <td>
            
                <?php if (hasPermission('users/edit.php')): ?>
                    <a href="edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                <?php endif; ?>
        

            </td>
        </tr>
    <?php endforeach; ?>
</table>


<?php
$content = ob_get_clean();
include '../layout.php';
?>
