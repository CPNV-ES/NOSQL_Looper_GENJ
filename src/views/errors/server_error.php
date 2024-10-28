<?php
$title = 'Method Not Allowed';

http_response_code(500);

ob_start();
?>

<h1>Error 500 - Internal Server Error</h1>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>