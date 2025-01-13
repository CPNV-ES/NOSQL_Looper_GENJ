<?php
/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description Manage a field view
 */

$title = 'ExerciseLooper';

ob_start();
?>

<link rel="stylesheet" href="/assets/css/user.css">

<header class="heading managing-users">
    <section class="container">
        <a href="/"><img src="/assets/img/logo.png"></a>
        <span class="exercise-label">Manage <?= $user->getUsername() ?></span>
    </section>
</header>
<main class="container">
    <h1>Update user</h1>
    <form action="/users/<?= $user->getId() ?>/edit" accept-charset="UTF-8" method="post">
        <div class="field">
            <label for="exercise_title">Role</label>
            <select name="role" id="role">
                <option value="student" <?= $user->getRole() === Role::Student ? 'selected' : '' ?>>Student</option>
                <option value="teacher" <?= $user->getRole() === Role::Teacher ? 'selected' : '' ?>>Teacher</option>
                <option value="dean" <?= $user->getRole() === Role::Dean ? 'selected' : '' ?>>Dean</option>
            </select>
        </div>

        <div class="actions">
            <input type="submit" name="commit" value="Update">
        </div>
    </form>
</main>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>