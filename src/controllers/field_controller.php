<?php

require_once MODEL_DIR . '/exercise.php';

class FieldController
{
	public function createField(int $exercise_id)
	{
		if (!isset($_POST['field']['label'], $_POST['field']['value_kind'])) {
			badRequest();
			return;
		}

		$exercise = null;

		$exercise = new Exercise($exercise_id);

		$kind = $this->kindStringToKindEnum($_POST['field']['value_kind']);

		$exercise->createField($_POST['field']['label'], $kind);

		header('Location: /exercises/' . $exercise_id . '/fields');
	}

	public function deleteField(int $exercise_id, int $field_id)
	{
		$exercise = null;

		$exercise = new Exercise($exercise_id);
		$field = new Field($field_id);

		if ($exercise->isFieldInExercise($field)) {
			$field->delete();
		}

		header('Location: /exercises/' . $exercise_id . '/fields');
	}

	public function editField(int $exercise_id, int $field_id)
	{
		$exercise = new Exercise($exercise_id);
		$field = new Field($field_id);

		if (!$exercise->isFieldInExercise($field)) {
			lost();
			return;
		}

		if (isset($_POST['field']['label'])) {
			$field->setLabel($_POST['field']['label']);
		}

		if (isset($_POST['field']['value_kind'])) {
			$field->setKind($this->kindStringToKindEnum($_POST['field']['value_kind']));
		}

		header('Location: /exercises/' . $exercise_id . '/fields/' . $field_id . '/edit');
	}

	private function kindStringToKindEnum(string $kind)
	{
		switch ($kind) {
			case 'single_line_list':
				return Kind::ListOfSingleLines;
			case 'multi_line':
				return Kind::MultiLineText;
			default:
			case 'single_line':
				return Kind::SingleLineText;
		}
	}
}
