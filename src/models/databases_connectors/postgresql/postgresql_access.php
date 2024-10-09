<?php

require MODEL_DIR . '/databases_connectors/databases_access.php';
require MODEL_DIR . '/databases_connectors/postgresql/postgresql.php';

class PostgresqlAccess implements DatabasesAccess
{
	private $postgresql;

	public function __construct($host, $port, $dbname, $postgres_user, $postgres_password)
	{
		$this->postgresql = new Postgresql($host, $port, $dbname, $postgres_user, $postgres_password);
		$this->create_db_if_not_exist();
	}

	public function doesExerciseExist(int $id): bool
	{
		return count($this->postgresql->select('SELECT id FROM exercises WHERE id = :id', [':id' => $id])) > 0;
	}

	public function createExercise(string $title): int
	{
		return (int)$this->postgresql->select('INSERT INTO exercises (title) VALUES (:title) RETURNING id', [':title' => $title])[0][0];
	}

	public function getExerciseTitle(int $id): string
	{
		$result = $this->postgresql->select('SELECT title FROM exercises WHERE id = :id', [':id' => $id]);
		return $result[0]['title'];
	}

	public function getExerciseStatus(int $id): string
	{
		$result = $this->postgresql->select('SELECT status FROM exercises WHERE id = :id', [':id' => $id]);
		return $result[0]['status'];
	}

	public function getExercises(int $status = -1): array
	{
		if ($status < 0) {
			return $this->postgresql->select('SELECT id FROM exercises');
		}
		return $this->postgresql->select('SELECT id FROM exercises WHERE status = :status', [':status' => $status]);
	}

	public function deleteExercise(int $id): void
	{
		$this->postgresql->modify('DELETE FROM exercises WHERE id = :id', [':id' => $id]);
	}

	private function create_db_if_not_exist()
	{
		if (count($this->postgresql->select("SELECT 1 FROM information_schema.tables WHERE table_name = 'exercises'")) < 1) {
			$this->postgresql->modify(file_get_contents(filename: MODEL_DIR . '/databases_connectors/postgresql/createdb.sql'));
		}
	}

	public function getExerciseStatus(int $id = -1): Status
	{
		$result = $this->postgresql->select('SELECT status FROM exercises WHERE id = :id', [':id' => $id]);
		return Status::from($result[0]['status']);
	}

	public function setExerciseStatus(int $id, Status $status)
	{
		$result = $this->postgresql->select('UPDATE exercises set status=:status WHERE id = :id', [':id' => $id, ':status' => $status->value]);
		return $result;
	}

	public function getFieldsCount(int $exercise_id): int
	{
		return $this->postgresql->select('SELECT COUNT(id) FROM fields WHERE exercise_id = :exercise_id', [':exercise_id' => $exercise_id])[0][0];
	}
}