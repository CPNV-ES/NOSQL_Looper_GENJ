<?php
$title = "Home";

ob_start();
?>

<h1>Home</h1>

<?php
$content = ob_get_clean();
require VIEW_DIR."/gabarit.php";
?>