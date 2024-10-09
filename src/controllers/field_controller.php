<?php

$entry = [
	'FieldController()' => [
		'GET' => [
			'/exercises/:id:int/fields/:idFields:int' => 'deleteField(:id:int, :idFields:int)'
		],
		'POST' => [
			'/exercises/:id:int/fields' => 'createField(:id:int)'
		]
	]
];

class FieldController
{
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

	public function deleteField(int $exercise_id, int $field_id)
	{
		$exercise = null;

		try {
			$exercise = new Exercise($exercise_id);
			$field = new Field($field_id);

			if ($exercise->isFieldInExercise($field)) {
				$field->delete();
			}
		} catch (Exception) {
			lost();
			return;
		}

		header('Location: /exercises/' . $exercise_id . '/fields');
	}
}
