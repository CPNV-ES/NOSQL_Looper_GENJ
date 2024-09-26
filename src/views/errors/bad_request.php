<?php
$title = 'Bad Request';

ob_start();
?>

<h1>Error 400 - Bad Request</h1>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>