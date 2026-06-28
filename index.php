<?php
ob_start();
include '../../init.php';
$conn = dbConnect();
$roles = $conn->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>


<h3>Roles</h3>


<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Role Name</th>
        <th>Actions</th>
    </tr>


    <?php foreach ($roles as $role): ?>
        <tr>
            <td><?= $role['id'] ?></td>
            <td><?= $role['role_name'] ?></td>
            <td>
                <a href="permissions.php?role_id=<?= $role['id'] ?>" class="btn btn-primary btn-sm">
                    Assign Permissions
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<?php
$content = ob_get_clean();
include '../layout.php';
?>
