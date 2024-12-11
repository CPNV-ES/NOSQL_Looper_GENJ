<?php

/**
 * @author Ethann Schneider, Geoffroy  Wildi, Jomana Kaempf, Nathan Chauveau
 * @version 11.12.2024
 * @description Register view
 */
$title = 'ExerciseLooper';

ob_start();
?>



<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>