<?php

include_once MODEL_DIR . '/exercise.php';

$entry = [
	'ExerciseController()' => [
		'POST' => [
			'/exercises' => 'createExercise()',
			'/exercises/:id:int/fields' => 'createField(:id:int)'
		]
	]
];

class ExerciseController
{
	public function createExercise()
	{
		if (!isset($_POST['exercise_title'])) {
			badRequest();
			return;
		}

		$exercise = Exercise::create($_POST['exercise_title']);
		header('Location: /exercises/' . $exercise->getId() . '/fields');
	}

	public function createField(int $exercise_id)
	{
		if (!isset($_POST['field']['label'], $_POST['field']['value_kind'])) {
			badRequest();
			return;
		}

		$exercise = null;

		try {
			$exercise = new Exercise($exercise_id);
		} catch (Exception $e) {
			lost();
			return;
		}

		$kind = null;

		switch ($_POST['field']['value_kind']) {
			case 'single_line':
				$kind = Kind::SingleLineText;
				break;
			case 'single_line_list':
				$kind = Kind::ListOfSingleLines;
				break;
			case 'multi_line':
				$kind = Kind::MultiLineText;
				break;
			default:
				badRequest();
				return;
		}

		$exercise->createField($_POST['field']['label'], $kind);

		header('Location: /exercises/' . $exercise_id . '/fields');
	}
}
