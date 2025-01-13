<?php
/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description show field result view
 */

$title = 'ExerciseLooper';

ob_start();
?>

<header class="heading results">
	<section class="container">
		<a href="/"><img src="/assets/img/logo.png"></a>
		<span class="exercise-label">Exercise: <a href="/exercises/<?=$exercise->getId()?>/results"><?=$exercise->getTitle()?></a></span>
	</section>
</header>

<main class="container">
	<h1><?= $field->getLabel()?></h1>

	<table>
		<thead>
			<tr>
				<th>Take</th>
				<th>Content</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($exercise->getFulfillments() as $fulfillment) :
				$fulfillmentField = new FulfillmentField($field->getId(), $fulfillment->getId());
				?>
			<tr>
				<td><a href="/exercises/<?=$exercise->getId()?>/fulfillments/<?=$fulfillment->getId()?>"><?=$fulfillment->getTimestamp()?></a></td>
				<?php if ($fulfillmentField->getDataCorrection() == Correction::Unverified):?>
                <td style="color:#e0a458;"><?=$fulfillmentField->getBody()?></td>
                <?php elseif ($fulfillmentField->getDataCorrection() == Correction::Correct):?>
                <td style="color:#419d78;"><?=$fulfillmentField->getBody()?></td>
                <?php elseif ($fulfillmentField->getDataCorrection() == Correction::Incorrect):?>
                <td style="color:#f45866;"><?=$fulfillmentField->getBody()?></td>
                <?php endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>