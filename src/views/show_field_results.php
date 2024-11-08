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
	<h1>10</h1>

	<table>
		<thead>
			<tr>
				<th>Take</th>
				<th>Content</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($exercise->$fulfillments as $fulfillment) : ?>
			<tr>
				<td><a href="/exercises/<?=$exercise->Id?>/fulfillments/<?=$fulfillment->Id?>"><?=$fulfillment->timestamp?></a></td>
				<td><?=$fulfillment->getData($field->id)?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>