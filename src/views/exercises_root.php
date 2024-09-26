<?php
$title = 'ExerciseLooper';

ob_start();
?>

<link rel="stylesheet" href="/assets/css/exercises_root.css">

<header class="heading answering">
	<section class="container">
		<a href="/"><img src="/assets/img/logo.png" /></a>
	</section>
</header>

<main class="container">
	<?php
		$exercises = [
			[
				'id' => 1,
				'name' => 'Anglais'
			],
			[
				'id' => 2,
				'name' => 'Francais'
			]
		]
?>
	<ul class="answering-list">
		<?php foreach ($exercises as $exercise): ?>
		<li class="row">
			<div class="column card">
				<div class="title">
					<?= htmlspecialchars($exercise['name']); ?>
				</div>
				<a class="button"
					href="/exercises/<?= $exercise['id']; ?>/fulfillments/new">Take
					it</a>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</main>
<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>