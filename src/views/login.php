<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description Create an exercise view
 */
$title = 'ExerciseLooper';

ob_start();
?>

<header class="heading managing">
    <section class="container">
        <a href="/"><img src="/assets/img/logo.png"></a>
        <span class="exercise-label">Login</span>
    </section>
</header>

<form>

</form>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>