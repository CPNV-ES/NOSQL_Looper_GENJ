<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description Home view
 */

$title = 'ExerciseLooper';

ob_start();
?>

<header class="dashboard">
  <section class="container">
    <p><img src="/assets/img/logo.png"></p>
    <h1>Exercise<br>Looper</h1>
  </section>
</header>

<div class="container dashboard">
  <section class="row">
    <?php if (isset($_SESSION['user'])): ?>
      <div class="column">
        <a class="button answering column" href="/exercises/answering">Take an exercise</a>
      </div>
      
      <?php if($user->getRole() == Role::Teacher || $user->getRole() == Role::Dean): ?>
      <div class="column">
        <a class="button managing column" href="/exercises/new">Create an exercise</a>
      </div>
      <div class="column">
        <a class="button results column" href="/exercises">Manage an exercise</a>
      </div>
      <?php endif; ?>
      <?php if ($user->getRole() == Role::Dean): ?>
      <div class="column">
        <a class="button managing-users column" href="/users">Manage users</a>
      </div>
      <?php endif; ?>
      <div class="column">
        <a class="button results column" href="/logout">Logout</a>
      </div>
    <?php else: ?>
      <div class="column">
        <a class="button results column" href="/login">Login</a>
      </div>
    <?php endif; ?>
  </section>
</div>
<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>