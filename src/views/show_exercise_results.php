<?php
$title = 'ExerciseLooper';

ob_start();
?>
<header class="heading results">
	<section class="container">
		<a href="/"><img src="/assets/img/logo.png"></a>
		<span class="exercise-label">Exercise: <a
				href="/exercises/<?=$exercise->Id?>/results"><?=$exercise->Name?></a></span>
	</section>
</header>
<main class="container">
	<table>
		<thead>
			<tr>
				<th>Take</th>
				<?php foreach ($exercise->fields as $i => $field): ?>
				<th><a
						href="/exercises/<?=$exercise->Id?>/results/<?=$field->Id?>"><?=$i + 1?></a>
				</th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($exercise->$fulfillments as $fulfillment) : ?>
			<tr>
				<td><a
						href="/exercises/<?=$exercise->Id?>/fulfillments/<?=$fulfillment->Id?>"><?=$fulfillment->timestamp?></a>
				</td>
				<?php foreach ($exercise->fields as $field):
					$len = strlen($fulfillment->getData($field->id));
					if ($len == 0):?>
				<td class="answer"><i class="fa fa-times empty"></i></td>
				<?php elseif ($len > 10):?>
				<td class="answer"><i class="fa fa-check-double filled"></i></td>
				<?php else: ?>
				<td class="answer"><i class="fa fa-check short"></i></td>
				<?php endif; ?>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</main>
<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>