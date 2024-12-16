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
        <span class="exercise-label">Manage users</span>
    </section>
</header>
<main class="container">
    <div class="user-list">
        <?php foreach ($users as $user): ?>
            <div class="user-item">
                <div class="title">
                    <?= htmlspecialchars($user->getUsername()); ?>
                </div>
                <a class="button" href="/users/<?= $user->getId(); ?>">Edit</a>
                <a class="button" href="/users/<?= $user->getId(); ?>/delete">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>