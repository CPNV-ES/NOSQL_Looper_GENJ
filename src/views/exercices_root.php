<header class="heading answering">
  <section class="container">
    <a href="/"><img src="/assets/img/logo.png" /></a>
  </section>
</header>

<main class="container">
 	<!DOCTYPE html>
<html>
	<head>
		<title>ExerciseLooper</title>
		<meta name="csrf-param" content="authenticity_token" />
		<meta name="csrf-token" content="c4DNz/7iDVI05ezE7+E9G7GJyaM4odT7rZnqJU2+C/3pz/mPAuQ4OXSmfF14CCfprUSJRXrDFCcZQyJUkVWD/g==" />
		<link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
		<link rel="stylesheet" href="/assets/css/exercises_root.css">
	</head>
	<body>
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
					<div class="title"><?= htmlspecialchars($exercise['name']); ?></div>
					<a class="button" href="/exercises/<?= $exercise['id']; ?>/fulfillments/new">Take it</a>
				</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</body>
</html>
</main>
