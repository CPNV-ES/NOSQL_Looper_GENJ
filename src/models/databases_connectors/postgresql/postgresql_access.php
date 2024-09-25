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

	public function isExcerciceExist(int $id): bool
	{
		return count($this->postgresql->select('SELECT id FROM excercices WHERE id = :id', [':id' => $id])) > 0;
	}

	public function createExercice(string $title): int
	{
		return (int)$this->postgresql->select('INSERT INTO excercices (title) VALUES (:title) RETURNING id', [':title' => $title])[0][0];
	}

	public function getExcerciceTitle(int $id): string
	{
		return $this->postgresql->select('SELECT title FROM excercices WHERE id = :id', [':id' => $id]);
	}

	private function create_db_if_not_exist()
	{
		$dir = scandir(MODEL_DIR . '/databases_connectors/postgresql/tables/');

		foreach ($dir as $file) {
			if (!str_ends_with($file, '.sql')) {
				continue;
			}

			$file = str_replace('.sql', '', $file);

			if (count($this->postgresql->select("SELECT 1 FROM information_schema.tables WHERE table_name = '$file'")) < 1) {
				$this->postgresql->modify(file_get_contents(filename: MODEL_DIR . '/databases_connectors/postgresql/tables/' . $file . '.sql'));
			}
		}
	}
}
