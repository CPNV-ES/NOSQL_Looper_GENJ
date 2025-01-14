<?php
/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description show exercise result view
 */

$title = 'ExerciseLooper';

ob_start();
?>
<header class="heading results">
	<section class="container">
		<a href="/"><img src="/assets/img/logo.png"></a>
		<span class="exercise-label">Exercise: <a
				href="/exercises/<?=$exercise->getId()?>/results"><?=$exercise->getTitle()?></a></span>
	</section>
</header>
<main class="container">
	<?php $limitDate = $exercise->getLimitDate(); ?>
	<?php if (isset($limitDate)): ?>
	<section class="limit-date">
		<p>Results limited to submissions before: <?=$limitDate->format('Y-m-d H:i:s.u')?></p>
	</section>
	<?php endif; ?>
	<table>
		<thead>
			<tr>
				<th>Take</th>
				<?php foreach ($exercise->getFields() as $field): ?>
				<th><a
						href="/exercises/<?=$exercise->getId()?>/results/<?=$field->getId()?>"><?=$field->getLabel()?></a>
				</th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($exercise->getFulfillments() as $fulfillment) : ?>
			<tr>
				<td><a href="/exercises/<?=$exercise->getId()?>/fulfillments/<?=$fulfillment->getId()?>"><?=$fulfillment->getTimestamp()->format('Y-m-d H:i:s.u')?></a>
				</td>
				<?php foreach ($exercise->getFields() as $i => $field):
					$fulfillmentField = new FulfillmentField($field->getId(), $fulfillment->getId());
					if ($fulfillmentField->getDataCorrection() == Correction::Unverified):?>
				<td class="answer"><i class="fa-solid fa-question" style="color: #e0a458;"></i></td>
				<?php elseif ($fulfillmentField->getDataCorrection() == Correction::Correct):?>
				<td class="answer"><i class="fa fa-check short"></i></td>
				<?php elseif ($fulfillmentField->getDataCorrection() == Correction::Incorrect): ?>
				<td class="answer"><i class="fa fa-times empty"></i></td>
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