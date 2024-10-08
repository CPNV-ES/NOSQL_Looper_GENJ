<?php
$title = 'Method Not Allowed';

ob_start();
?>

<h1>Error 500 - Internal Server Error</h1>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>