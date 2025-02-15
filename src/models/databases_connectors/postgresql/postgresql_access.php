<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description PostgresqlAccess class implementing DatabasesAccess
 */

require_once MODEL_DIR . '/databases_connectors/databases_access.php';
require_once MODEL_DIR . '/databases_connectors/postgresql/postgresql.php';

/**
 * Class PostgresqlAccess
 *
 * Provides an implementation of DatabasesAccess for PostgreSQL databases.
 */
class PostgresqlAccess implements DatabasesAccess
{
	private $postgresql;

	/**
	 * PostgresqlAccess constructor.
	 *
	 * @param string $host The host of the PostgreSQL server.
	 * @param int $port The port of the PostgreSQL server.
	 * @param string $dbname The name of the database.
	 * @param string $postgres_user The username for the PostgreSQL server.
	 * @param string $postgres_password The password for the PostgreSQL server.
	 */
	public function __construct($host, $port, $dbname, $postgres_user, $postgres_password)
	{
		$this->postgresql = new Postgresql($host, $port, $dbname, $postgres_user, $postgres_password);
		$this->create_db_if_not_exist();
	}

	public function doesExerciseExist(int $id): bool
	{
		return count($this->postgresql->select('SELECT id FROM exercises WHERE id = :id', [':id' => $id])) > 0;
	}

	public function createExercise(string $title, DateTime|null $limitDate): int
	{
		if ($limitDate == null) {
			return (int)$this->postgresql->select('INSERT INTO exercises (title) VALUES (:title) RETURNING id', [':title' => $title])[0][0];
		}
		return (int)$this->postgresql->select('INSERT INTO exercises (title, limit_date) VALUES (:title, :limit_date) RETURNING id', [':title' => $title, ':limit_date' => $limitDate->format('Y-m-d H:i:s')])[0][0];
	}

	public function getExerciseTitle(int $id): string
	{
		$result = $this->postgresql->select('SELECT title FROM exercises WHERE id = :id', [':id' => $id]);
		return $result[0]['title'];
	}

	public function getExerciseStatus(int $id): int
	{
		$result = $this->postgresql->select('SELECT status FROM exercises WHERE id = :id', [':id' => $id]);
		return $result[0]['status'];
	}

	public function getExercises(int $status = ALL_EXERCISES): array
	{
		if ($status == ALL_EXERCISES) {
			return $this->postgresql->select('SELECT id FROM exercises');
		}
		return $this->postgresql->select('SELECT id FROM exercises WHERE status = :status', [':status' => $status]);
	}

	public function getFields(int $exercise_id): array
	{
		return $this->postgresql->select('SELECT id FROM fields WHERE exercise_id = :exercise_id', [':exercise_id' => $exercise_id]);
	}

	public function doesFieldExist(int $id): bool
	{
		return count($this->postgresql->select('SELECT id FROM fields WHERE id = :id', [':id' => $id])) > 0;
	}

	public function doesFulfillmentExist(int $id): bool
	{
		return count($this->postgresql->select('SELECT id FROM fulfillments WHERE id = :id', [':id' => $id])) > 0;
	}

	public function getFulfillmentFields(int $id): array
	{
		return $this->postgresql->select('SELECT fulfillments_data.field_id FROM fulfillments INNER JOIN fulfillments_data ON fulfillments.id = fulfillments_data.fulfillment_id WHERE fulfillments.id = :id ORDER BY fulfillments_data.field_id', [':id' => $id]);
	}

	public function getFulfillmentBody(int $field_id, int $fulfillment_id): string
	{
		return $this->postgresql->select('SELECT fulfillments_data.body FROM fulfillments INNER JOIN fulfillments_data ON fulfillments.id = fulfillments_data.fulfillment_id WHERE fulfillments.id = :id AND fulfillments_data.field_id = :field_id', [':id' => $fulfillment_id, ':field_id' => $field_id])[0][0];
	}

	public function getFulfillmentTimestamp(int $id): DateTime
	{
		$timestamp = $this->postgresql->select('SELECT fulfillments.creation_date FROM fulfillments WHERE fulfillments.id = :id', [':id' => $id])[0][0];
		return new DateTime($timestamp);
	}

	public function setFulfillmentBody(int $field_id, int $fulfillment_id, string $body): void
	{
		$this->postgresql->modify('UPDATE fulfillments_data SET body = :body WHERE fulfillment_id = :id AND field_id = :field_id', [':id' => $fulfillment_id, ':field_id' => $field_id, ':body' => $body]);
	}

	public function createFulfillment(int $exercise_id): int
	{
		return (int)$this->postgresql->select('INSERT INTO fulfillments(exercise_id) VALUES (:exercise_id) RETURNING id', [':exercise_id' => $exercise_id])[0][0];
	}

	public function getFulfillments(int $exercise_id)
	{
		return $this->postgresql->select('SELECT id FROM public.fulfillments WHERE exercise_id = :exercise_id ORDER BY creation_date ASC ', [':exercise_id' => $exercise_id]);
	}

	public function createFulfillmentField(int $field_id, int $fulfillment_id, string $body): void
	{
		$this->postgresql->modify('INSERT INTO fulfillments_data(field_id, fulfillment_id, body) VALUES (:field_id, :fulfillment_id, :body)', [':field_id' => $field_id, ':fulfillment_id' => $fulfillment_id, ':body' => $body]);
	}

	public function getFieldLabel(int $id): string
	{
		return $this->postgresql->select('SELECT label FROM fields WHERE id = :id', [':id' => $id])[0]['label'];
	}

	public function getFieldAnswer(int $id): string
	{
		return $this->postgresql->select('SELECT answer FROM fields WHERE id = :id', [':id' => $id])[0]['answer'];
	}

	public function getFieldKind(int $id): int
	{
		return $this->postgresql->select('SELECT kind FROM fields WHERE id = :id', [':id' => $id])[0]['kind'];
	}

	public function createField(int $exercise_id, string $label, string $answer, int $kind): int
	{
		return (int)$this->postgresql->select('INSERT INTO fields (label, answer, kind, exercise_id) VALUES (:label, :answer, :kind, :exercise_id) RETURNING id', [':label' => $label, ':answer' => $answer, ':kind' => $kind, ':exercise_id' => $exercise_id])[0][0];
	}

	public function deleteField(int $id): void
	{
		$this->postgresql->modify('DELETE FROM fields WHERE id = :id', [':id' => $id]);
	}

	public function isFieldInExercise(int $exercise_id, int $field_id): bool
	{
		return count($this->postgresql->select('SELECT id FROM fields WHERE exercise_id = :exercise_id AND id = :field_id', [':exercise_id' => $exercise_id, ':field_id' => $field_id])) > 0;
	}

	public function isFulfillmentInExercise(int $exercise_id, int $fulfillment_id): bool
	{
		return count($this->postgresql->select('SELECT id FROM fulfillments WHERE exercise_id = :exercise_id AND id = :fulfillment_id', [':exercise_id' => $exercise_id, ':fulfillment_id' => $fulfillment_id])) > 0;
	}

	public function setFieldLabel(int $id, string $label): void
	{
		$this->postgresql->modify('UPDATE fields SET label = :label WHERE id = :id', [':label' => $label, ':id' => $id]);
	}

	public function setFieldAnswer(int $id, string $answer): void
	{
		$this->postgresql->modify('UPDATE fields SET answer = :answer WHERE id = :id', [':answer' => $answer, ':id' => $id]);
	}

	public function setFieldKind(int $id, int $kind): void
	{
		$this->postgresql->modify('UPDATE fields SET kind = :kind WHERE id = :id', [':kind' => $kind, ':id' => $id]);
	}

	public function deleteExercise(int $id): void
	{
		$this->postgresql->modify('DELETE FROM exercises WHERE id = :id', [':id' => $id]);
	}

	public function setExerciseStatus(int $id, int $status)
	{
		$result = $this->postgresql->select('UPDATE exercises set status=:status WHERE id = :id', [':id' => $id, ':status' => $status]);
		return $result;
	}

	public function getFieldsCount(int $exercise_id): int
	{
		return $this->postgresql->select('SELECT COUNT(id) FROM fields WHERE exercise_id = :exercise_id', [':exercise_id' => $exercise_id])[0][0];
	}

	public function getExerciseByFieldId(int $field_id): int
	{
		return $this->postgresql->select('SELECT exercise_id FROM fields WHERE id = :field_id', ['field_id' => $field_id])[0][0];
	}

	public function getExerciseByFulfillmentId(int $fulfillment_id): int
	{
		return $this->postgresql->select('SELECT exercise_id FROM fulfillments WHERE id = :fulfillment_id', ['fulfillment_id' => $fulfillment_id])[0][0];
	}

    public function getFulfillmentDataCorrection(int $field_id, int $fulfillment_id): string
    {
        return $this->postgresql->select('SELECT correction FROM fulfillments_data WHERE fulfillment_id = :fulfillment_id AND field_id = :field_id', [':fulfillment_id' => $fulfillment_id, ':field_id' => $field_id])[0][0];
    }

    public function setAnswerCorrection(int $field_id, int $fulfillment_id, int $correction): void
    {
        $this->postgresql->modify('UPDATE fulfillments_data SET correction = :correction WHERE fulfillment_id = :fulfillment_id AND field_id = :field_id', [':fulfillment_id' => $fulfillment_id, ':field_id' => $field_id, ':correction' => $correction]);
    }

	public function doesUserExist(int $id): bool
	{
		return count($this->postgresql->select('SELECT id FROM users WHERE id = :id', [':id' => $id])) > 0;
	}

	public function getUserUsername(int $id): string
	{
		return $this->postgresql->select('SELECT username FROM users WHERE id = :id', [':id' => $id])[0]['username'];
	}

	public function getUserRole(int $id): int
	{
		return $this->postgresql->select('SELECT role FROM users WHERE id = :id', [':id' => $id])[0]['role'];
	}

	public function getUsers(int $role = ALL_USER): array
	{
		if ($role == ALL_USER) {
			return $this->postgresql->select('SELECT id FROM users');
		}
		return $this->postgresql->select('SELECT id FROM users WHERE role = :role', [':role' => $role]);
	}

	public function deleteUser(int $userId): void
	{
		$this->postgresql->modify('DELETE FROM users WHERE id = :id', [':id' => $userId]);
	}

	public function setUserRole(int $id, int $role): void
	{
		$this->postgresql->modify('UPDATE users SET role = :role WHERE id = :id', [':role' => $role, ':id' => $id]);
	}

	public function findUserIdByUsername(string $username): int
	{
		if (count($this->postgresql->select('SELECT id FROM users WHERE username = :username', [':username' => $username])) > 0) {
			return $this->postgresql->select('SELECT id FROM users WHERE username = :username', [':username' => $username])[0]['id'];
		}
		return -1;
	}

	public function createUser(string $username, string $hashedPassword): int
	{
		return (int)$this->postgresql->select('INSERT INTO users (username, password) VALUES (:username, :password) RETURNING id', [':username' => $username, ':password' => $hashedPassword])[0][0];
	}

	public function getPassword(int $id): string
	{
		return $this->postgresql->select('SELECT password FROM users WHERE id = :id', [':id' => $id])[0][0];
	}

	public function isUserExistByUsername(string $username): bool
	{
		if (count($this->postgresql->select('SELECT id FROM users WHERE username = :username', [':username' => $username])) > 0) {
			return true;
		}
		return false;
	}

	public function getExerciseLimitDate(int $exceriseId): DateTime|null
	{
		$result = $this->postgresql->select('SELECT limit_date FROM exercises WHERE id = :id', [':id' => $exceriseId]);
		if (isset($result[0]['limit_date'])) {
			return null;
		}
		return new DateTime($result[0]['limit_date']);
	}

	public function getExercisesByLimitDateAndIsAnswering(DateTime $date): array
	{
		return $this->postgresql->select(
			'SELECT id FROM exercises WHERE limit_date <= :date AND status = 1',
			[
				':date' => $date->format('Y-m-d')
			]
		);
	}

	private function create_db_if_not_exist()
	{
		if (count($this->postgresql->select("SELECT 1 FROM information_schema.tables WHERE table_name = 'exercises'")) < 1) {
			foreach (explode(';', file_get_contents(filename: MODEL_DIR . '/databases_connectors/postgresql/createdb.sql')) as $query) {
				if ($query == '') {
					continue;
				}
				try {
					$this->postgresql->modify($query);
				} catch (PDOException $e) {
					continue;
				}
			}
		}
	}
}
