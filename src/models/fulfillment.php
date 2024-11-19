<?php

require_once MODEL_DIR . '/databases_connectors/databases_choose.php';
require_once MODEL_DIR . '/fulfillment_field.php';
require_once MODEL_DIR . '/field.php';
require_once MODEL_DIR . '/exercise.php';

class Fulfillment
{
	private DatabasesAccess $database_access;
	private int $id;

	public function __construct(int $id)
	{
		$this->id = $id;

		$this->database_access = (new DatabasesChoose())->getDatabase();

		if (!$this->database_access->doesFulfillmentExist($id)) {
			throw new FulfillmentNotFoundException();
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTimestamp()
	{
		return $this->database_access->getFulfillmentTimestamp($this->id);
	}

	public function createFields(Field $field, string $body)
	{
		if ($this->getExercise()->getStatus() != Status::Answering) {
			throw new ExerciseNotInAnsweringStatus();
		}

		$this->database_access->createFulfillmentField($field->getId(), $this->id, $body);

		return new FulfillmentField($field->getId(), $this->id);
	}

	public function getFields()
	{
		$fulfillment = [];

		foreach ($this->database_access->getFulfillmentFields($this->id) as $field) {
			$fulfillment[] = new FulfillmentField($field[0], $this->id);
		}

		return $fulfillment;
	}

	public function getExercise()
	{
		return new Exercise($this->database_access->getExerciseByFulfillmentId($this->id));
	}
}

class FulfillmentNotFoundException extends LooperException
{
	public function __construct($message = 'The fulfillment does not exist', $code = 0, Exception $previous = null)
	{
		parent::__construct(404, 'fulfillment not found', $message, $code, $previous);
	}
}
