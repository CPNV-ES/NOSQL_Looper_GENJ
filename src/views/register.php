<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description Create an exercise view
 */
$title = 'ExerciseLooper';

ob_start();
?>



<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>