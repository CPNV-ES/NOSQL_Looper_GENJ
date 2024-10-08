<?php

require_once MODEL_DIR . '/databases_connectors/databases_choose.php';

enum Status: int
{
	case Building = 0;
	case Answering = 1;
	case Closed = 2;
}

class Exercises
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

	public static function getExercises(Status $status = null)
	{
		$database_access = (new DatabasesChoose())->getDatabase();
		$exercises_data = [];
		if ($status == null) {
			$exercises_data = $database_access->getExercises();
		} else {
			$exercises_data = $database_access->getExercises($status->value);
		}

		$exercises = [];
		foreach ($exercises_data as $exercise_data) {
			$exercise = new self($exercise_data['id']);
			$exercises[] = $exercise;
		}

		return $exercises;
	}
	public function getStatus(): mixed
	{
		return $this->database_access->getExerciseStatus($this->id);
	}

	public function setExerciseAs(Status $status)
	{
		$this->database_access->setExerciseStatus($this->id, $status);
	}
}
