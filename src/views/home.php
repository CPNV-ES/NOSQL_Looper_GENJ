<?php
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
    <div class="column">
      <a class="button answering column" href="/exercises/answering">Take an exercise</a>
    </div>
    <div class="column">
      <a class="button managing column" href="/exercises/new">Create an exercise</a>
    </div>
    <div class="column">
      <a class="button results column" href="/exercises">Manage an exercise</a>
    </div>
  </section>
</div>
<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>