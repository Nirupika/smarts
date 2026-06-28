<?php
ob_start();
?>
<div>
    <h2>My About Page</h2>
    <p>This is the about Page of the SMARTS Website</P>
</div>
<?php
$content= ob_get_clean();
include'layout.php';
?>
