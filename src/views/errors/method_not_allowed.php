<?php
$title = 'Method Not Allowed';

ob_start();
?>

<h1>Error 405 - Method Not Allowed</h1>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>