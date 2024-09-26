<?php

require_once MODEL_DIR . '/databases_connectors/databases_choose.php';

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

	public static function getExercises()
	{
		$database_access = (new DatabasesChoose())->getDatabase();
		$exercises_data = $database_access->getExercises();

		$exercises = [];
		foreach ($exercises_data as $exercise_data) {
			$exercise = new self($exercise_data['id']);
			$exercises[] = $exercise;
		}

		return $exercises;
	}

	public static function getExercisesAnswering()
	{
		$database_access = (new DatabasesChoose())->getDatabase();
		$exercises_data = $database_access->getExercisesAnswering();
	
		$exercises = [];
		foreach ($exercises_data as $exercise_data) {
			$exercise = new self($exercise_data['id']);
			$exercises[] = $exercise;
		}
	
		return $exercises;
	}
}
