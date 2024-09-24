<?php
$title = 'ExerciseLooper';

ob_start();
?>
<header class="heading managing">
  <section class="container">
    <a href="/"><img src="/assets/img/logo.png"></a>
    <span class="exercise-label">New exercise</span>
  </section>
</header>

<main class="container">
  <title>ExerciseLooper</title>
  <h1>New Exercise</h1>
  <form action="" accept-charset="UTF-8" method="post">
    <div class="field">
      <label for="exercise_title">Title</label>
      <input type="text" name="exercise_title" id="exercise_title">
    </div>

    <div class="actions">
      <input type="submit" name="commit" value="Create Exercise">
    </div>
  </form>
</main>
<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>