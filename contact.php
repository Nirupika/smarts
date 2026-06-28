<?php
ob_start();
?>
<div>
    <h2>My Contact page </h2>
    <p>This is the Contact page of the smarts Website </p>
</div>

<?php
$content= ob_get_clean();
include'layout.php';
?>