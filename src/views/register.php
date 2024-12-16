<?php

/**
 * @author Ethann Schneider, Geoffroy  Wildi, Jomana Kaempf, Nathan Chauveau
 * @version 11.12.2024
 * @description Register view
 */
$title = 'ExerciseLooper';

ob_start();
?>

<header class="heading managing">
    <section class="container">
        <a href="/"><img src="/assets/img/logo.png"></a>
        <span class="exercise-label">Login</span>
        <?php if ($_SESSION['state'] == 'login'): ?>
            <a class="link-label" href="/Logout">Log in</a>
        <?php else: ?>
            <a class="link-label" href="/Logout">Log out</a>
        <?php endif; ?>
    </section>
</header>

<main class="container">
    <div class="column float-right actions">
        <label for="exercise_title">Already having an account ?</label>
        <a class="button results column" href="/login">Login</a>
    </div>
    <h1>Register</h1>
    <title>ExerciseLooper</title>
    <form action="/register" accept-charset="UTF-8" method="post">
        <div class="field">
            <label for="exercise_title">Username</label>
            <input type="text" name="user_username" id="user_username">
        </div>
        <div class="field">
            <label for="exercise_title">Password</label>
            <input type="text" name="user_password" id="user_password">
        </div>


        <div class="actions">
            <input type="submit" name="commit" value="Login">
        </div>
    </form>


</main>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>