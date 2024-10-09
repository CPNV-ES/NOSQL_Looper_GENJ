<?php

$entry = [
	'FieldController()' => [
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
}
