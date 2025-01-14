<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description This file contains the fulfillment controller, which handles fulfillment actions.
 */

include_once MODEL_DIR . '/exercise.php';
include_once MODEL_DIR . '/user.php';

/**
 * FulfillmentController
 * This controller class handles actions related to the creation and editing of fulfillments.
 */
class FulfillmentController
{
	/**
	 * Create a fulfillment
	 * This method handles the creation of a fulfillment for a specific exercise, identified by `$exercise_id`.
	 * @param User $user current authenticated user
	 * @param  int $exercise_id The ID of the exercise for which a new fulfillment is being created.
	 * @return void This function performs creation and redirects, but does not return a value.
	 */
	public function createFulfillment(User $user, int $exercise_id)
	{
		if (!isset($_POST['fulfillment']['answers_attributes'])) {
			badRequest();
			return;
		}

		$exercise = new Exercise($exercise_id);

		$fields = $exercise->getFields();

		foreach ($_POST['fulfillment']['answers_attributes'] as $answers_attribute) {
			if (!isset($answers_attribute['field_id'], $answers_attribute['value'])) {
				badRequest();
				return;
			}
			if (!$exercise->isFieldInExercise(new Field($answers_attribute['field_id']))) {
				badRequest();
				return;
			}
		}
		$fulfillment = $exercise->createFulfillment();

		foreach ($fields as $field) {
			$fulfillment->createFields($field, $_POST['fulfillment']['answers_attributes'][$field->getId()]['value']);
		}

		header('Location: /exercises/' . $exercise->getId() . '/fulfillments/' . $fulfillment->getId() . '/edit');
	}

	/**
	 * Edit a fulfillment
	 * This method handles the editing of an existing fulfillment, identified by `$fulfillment_id`,
	 * for a specific exercise, identified by `$exercise_id`.
	 *
	 * @param User $user current authenticated user
	 * @param int $exercise_id The ID of the exercise associated with the fulfillment.
	 * @param int $fulfillment_id The ID of the fulfillment that is being edited.
	 * @return void This function updates the fulfillment and redirects, without returning a value.
	 */
	public function editFulfillment(User $user, int $exercise_id, int $fulfillment_id)
	{
		if (!isset($_POST['fulfillment']['answers_attributes'])) {
			badRequest();
			return;
		}

		$exercise = new Exercise($exercise_id);

		$fulfillment = new Fulfillment($fulfillment_id);

		foreach ($_POST['fulfillment']['answers_attributes'] as $answers_attribute) {
			if (!isset($answers_attribute['field_id'], $answers_attribute['value'])) {
				badRequest();
				return;
			}

			$fulfillment_data = new FulfillmentField($answers_attribute['field_id'], $fulfillment_id);

			$fulfillment_data->setBody($answers_attribute['value']);
		}

		header('Location: /exercises/' . $exercise->getId() . '/fulfillments/' . $fulfillment->getId() . '/edit');
	}

	/**
	 * Set a answer value
	 * This method handles the editing of an existing fulfillment, identified by `$fulfillment_id`,
	 * for a specific exercise, identified by `$exercise_id`.
	 *
	 * @param User $user current authenticated user
	 * @param int $exercise_id The ID of the exercise associated with the fulfillment.
	 * @param int $fulfillment_id The ID of the fulfillment that is being edited.
	 * @return void This function updates the fulfillment and redirects, without returning a value.
	 */
	public function setAnswerCorrection(User $teacher, int $exercise_id, int $fulfillment_id)
	{
		if (!isset($_POST['fulfillment']['field_id']) || !isset($_POST['fulfillment']['correction'])) {
			badRequest();
			return;
		}

		$exercise = new Exercise($exercise_id);

		$fulfillment = new Fulfillment($fulfillment_id);

		$fulfillment_data = new FulfillmentField($_POST['fulfillment']['field_id'], $fulfillment_id);

		switch ($_POST['fulfillment']['correction']) {
			case 'correct':
				$fulfillment_data->setCorrection(Correction::Correct);
				break;
			case 'incorrect':
				$fulfillment_data->setCorrection(Correction::Incorrect);
				break;
			default:
				badRequest();
				return;
		}

		header('Location: /exercises/' . $exercise_id . '/fulfillments/' . $fulfillment_id);
	}
}
