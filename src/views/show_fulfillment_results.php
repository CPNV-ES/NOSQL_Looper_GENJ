<?php
$title = 'ExerciseLooper';

ob_start();
?>


<header class="heading results">
	<section class="container">
		<a href="/"><img src="/assets/img/logo.png"></a>
		<span class="exercise-label">Exercise: <a href="/exercises/<?=$exercise->id?>/results"><?=$exercise->title?></a></span>
	</section>
</header>

<main class="container">
	<h1><?=$fulfillment->timestamp?></h1>
	<dl class="answer">
		<?php foreach ($exercise->fields as $i => $field): ?>
		<dt><?= $i + 1 ?></dt>
		<dd><?= $fulfillment->getData($field->id) ?></dd>
		<?php endforeach; ?>
	</dl>

	<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>