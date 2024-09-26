<?php

require_once MODEL_DIR . '/databases_connectors/databases_choose.php';

class exercises
{
	private DatabasesAccess $database_access;
	private int $id;

	public function __construct(int $id)
	{
		$this->database_access = (new DatabasesChoose())->getDatabase();
		if (!$this->database_access->doesexerciseExist($id)) {
			throw new Exception('The exercise does not exist');
		}

		$this->id = $id;
	}

	public static function create($title): self
	{
		$database_access = (new DatabasesChoose())->getDatabase();
		return new self($database_access->createExercise($title));
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTitle()
	{
		return $this->database_access->getexerciseTitle($this->id);
	}
}
