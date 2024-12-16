<?php

/**
 * @author Ethann Schneider, Geoffroy  Wildi, Jomana Kaempf, Nathan Chauveau
 * @version 11.12.2024
 * @description Login view
 */
$title = 'ExerciseLooper';

ob_start();
?>

<header class="heading managing">
    <section class="container">
        <a href="/"><img src="/assets/img/logo.png"></a>
        <span class="exercise-label">Login</span>
        <?php if ($_SESSION['user']): ?>
            <a class="link-label" href="/Logout"><? $_SESSION['user'] ?></a>
        <?php else: ?>
            <a class="link-label" href="/Logout">Log out</a>
        <?php endif; ?>
    </section>
</header>

<main class="container">
    <div class="column float-right actions">
        <label for="exercise_title">Don't have an account yet ?</label>
        <a class="button results column" href="/register">Register</a>
    </div>
    <title>ExerciseLooper</title>
    <h1>Login</h1>
    <form action="/login" accept-charset="UTF-8" method="post">
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