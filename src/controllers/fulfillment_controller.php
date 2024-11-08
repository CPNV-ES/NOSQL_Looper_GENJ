<?php

include_once MODEL_DIR . '/exercise.php';

$entry = [
	'FulfillmentController()' => [
		'POST' => [
			'/exercises/:id:int/fulfillments' => 'createFulfillment(:id:int)'
		]
	]
];

class FulfillmentController
{
    public function createFulfillment(int $exercise_id)
	{
        if (!isset($_POST['fulfillment']['answers_attributes'])){
            badRequest();
            return;
        }

		$exercise = new Exercise($exercise_id);

		$fields = $exercise->getFields();

		foreach($_POST['fulfillment']['answers_attributes'] as $answers_attribute) {
			if(!isset($answers_attribute["field_id"], $answers_attribute["value"])) {
				badRequest();
            	return;
			}
			if(!$exercise->isFieldInExercise(new Field($answers_attribute["field_id"]))) {
				badRequest();
            	return;
			}
		}
		$fulfillment = $exercise->createFulfillment();

		foreach($fields as $field) {
			$fulfillment->createFields($field, $_POST['fulfillment']['answers_attributes'][$field->getId()]["value"]);
		}

		header('Location: /exercises/' . $exercise->getId() . '/fulfillments/' . $fulfillment->getId() . '/edit');
	}
}