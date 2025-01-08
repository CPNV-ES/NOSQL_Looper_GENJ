<?php
/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description show fulfillment result view
 */

$title = 'ExerciseLooper';

ob_start();
?>


<header class="heading results">
	<link rel="stylesheet" href="/assets/css/answer.css">
	<section class="container">
		<a href="/"><img src="/assets/img/logo.png"></a>
		<span class="exercise-label">Exercise: <a
				href="/exercises/<?=$exercise->getId()?>/results"><?=$exercise->getTitle()?></a></span>
	</section>
</header>

<main class="container">
	<h1><?=$fulfillment->getTimestamp()?></h1>
	<dl class="answer">
		<?php foreach ($fulfillment->getFields() as $i => $field): ?>
		<dt><a
				href="/exercises/<?= $exercise->getId() ?>/results/<?= $field->getId() ?>"><?= $field->getLabel() ?></a>
		</dt>
		<dd>Student answer: <?= $field->getBody() ?></dd>

		<dd>
			<form
				action="/exercises/<?= $exercise->getId() ?>/fulfillments/<?= $fulfillment->getId() ?>/correction"
				method="POST">
				<div class="feur">
					<input type="radio" id="correct" name="fulfillment[correction]" value="correct"
						onchange="this.form.submit()">
					<label for="correct">Correct</label>
				</div>

				<div class="feur">
					<input type="radio" id="incorrect" name="fulfillment[correction]" value="incorrect"
						onchange="this.form.submit()">
					<label for="incorrect">Incorrect</label>
				</div>
			</form>
		</dd>

		<dd>Correct answer: <?= $field->getAnswer() ?></dd>
		<?php endforeach; ?>
	</dl>

	<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>