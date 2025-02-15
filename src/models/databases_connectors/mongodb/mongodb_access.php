<?php

/**
 * @version 29.11.2024
 * @description MongodbAccess class implementing DatabasesAccess
 */

require_once MODEL_DIR . '/databases_connectors/databases_access.php';
require_once MODEL_DIR . '/databases_connectors/mongodb/mongodb.php';

/**
 * Class MongodbAccess
 *
 * Provides an implementation of DatabasesAccess for MongoDB databases.
 */
class MongodbAccess implements DatabasesAccess
{
	private $db;
	private $exercises;
	private $fields;
	private $fulfillments;
	private $fulfillments_data;
	private $users;

	/**
	 * MongodbAccess constructor.
	 *
	 * @param string $host The host of the MongoDB server.
	 * @param int $port The port of the MongoDB server.
	 * @param string $mongo_user The username for the MongoDB server.
	 * @param string $mongo_password The password for the MongoDB server.
	 */
	public function __construct($host, $port, $mongo_user, $mongo_password)
	{
		$this->db = new MongoDB($host, $port, $mongo_user, $mongo_password);

		//Direct setup on collections
		$this->exercises = $this->db->mongodb->exercises;
		$this->fields = $this->db->mongodb->fields;
		$this->fulfillments = $this->db->mongodb->fulfillments;
		$this->fulfillments_data = $this->db->mongodb->fulfillments_data;
		$this->users = $this->db->mongodb->users;
	}

	public function doesExerciseExist(int $id): bool
	{
		$result = $this->db->find($this->exercises, ['id' => $id]);
		return count($result) > 0;
	}

	public function createExercise(string $title, DateTime|null $limitDate): int
	{
		if ($limitDate == null) {
			return $this->db->insert($this->exercises, ['title' => $title, 'status' => 0])[0]['id'];
		}
		return $this->db->insert($this->exercises, ['title' => $title, 'status' => 0, 'limit_date' => new MongoDB\BSON\UTCDateTime($limitDate)])[0]['id'];
	}

	public function getExerciseTitle(int $id): string
	{
		$result = $this->db->find($this->exercises, ['id' => $id], ['projection' => ['title' => 1]]);
		return $result[0]['title'];
	}

	public function getExerciseStatus(int $id): int
	{
		$result = $this->db->find($this->exercises, ['id' => $id], ['projection' => ['status' => 1]]);
		return $result[0]['status'];
	}

	public function getExercises(int $status = ALL_EXERCISES): array
	{
		if ($status == ALL_EXERCISES) {
			$result = $this->db->find($this->exercises, [], ['projection' => ['id' => 1]]);
		} else {
			$result = $this->db->find($this->exercises, ['status' => $status], ['projection' => ['id' => 1]]);
		}

		return $result;
	}

	public function getFields(int $exercise_id): array
	{
		$result = $this->db->find($this->fields, ['exercise_id' => $exercise_id], ['projection' => ['id' => 1]]);
		return $result;
	}

	public function doesFieldExist(int $id): bool
	{
		$result = $this->db->find($this->fields, ['id' => $id], ['projection' => ['id' => 1]]);
		return count($result) > 0;
	}

	public function doesFulfillmentExist(int $id): bool
	{
		$result = $this->db->find($this->fulfillments, ['id' => $id], ['projection' => ['id' => 1]]);
		return count($result) > 0;
	}

	public function getFulfillmentFields(int $id): array
	{
		$result = $this->db->find($this->fulfillments_data, ['fulfillment_id' => $id], ['projection' => ['field_id' => 1]]);
		return $result;
	}

	public function getFulfillmentBody(int $field_id, int $fulfillment_id): string
	{
		$result = $this->db->find($this->fulfillments_data, ['fulfillment_id' => $fulfillment_id, 'field_id' => $field_id], ['projection' => ['body' => 1]]);
		return $result[0]['body'];
	}

	public function getFulfillmentTimestamp(int $id): DateTime
	{
		$result = $this->db->find($this->fulfillments, ['id' => $id], ['projection' => ['creation_date' => 1]]);
		return $result[0]['creation_date']->toDateTime();
	}

	public function setFulfillmentBody(int $field_id, int $fulfillment_id, string $body): void
	{
		$this->db->update($this->fulfillments_data, ['fulfillment_id' => $fulfillment_id, 'field_id' => $field_id], ['$set' => ['body' => $body]]);
	}

	public function createFulfillment(int $exercise_id): int
	{
		$result = $this->db->insert($this->fulfillments, ['exercise_id' => $exercise_id, 'creation_date' => new MongoDB\BSON\UTCDateTime()]);
		return $result[0]['id'];
	}

	public function getFulfillments(int $exercise_id)
	{
		$result = $this->db->find($this->fulfillments, ['exercise_id' => $exercise_id], ['projection' => ['id' => 1], 'sort' => ['timestamp' => 1]]);
		return $result;
	}

	public function createFulfillmentField(int $field_id, int $fulfillment_id, string $body): void
	{
		$result = $this->db->insert($this->fulfillments_data, ['field_id' => $field_id, 'fulfillment_id' => $fulfillment_id, 'body' => $body, 'correction' => 0]);
	}

	public function getFieldLabel(int $id): string
	{
		$result = $this->db->find($this->fields, ['id' => $id], ['projection' => ['label' => 1]]);
		return $result[0]['label'];
	}

    public function getFieldAnswer(int $id): string
    {
        $result = $this->db->find($this->fields, ['id' => $id], ['projection' => ['answer' => 1]]);
        return $result[0]['answer'];
    }

	public function getFieldKind(int $id): int
	{
		$result = $this->db->find($this->fields, ['id' => $id], ['projection' => ['kind' => 1]]);
		return $result[0]['kind'];
	}

	public function createField(int $exercise_id, string $label, string $answer, int $kind): int
	{
		$result = $this->db->insert($this->fields, ['label' => $label, 'answer' => $answer, 'kind' => $kind, 'exercise_id' => $exercise_id]);
		return $result[0]['id'];
	}

	public function deleteField(int $id): void
	{
		$this->db->remove($this->fields, ['id' => $id]);
	}

	public function isFieldInExercise(int $exercise_id, int $field_id): bool
	{
		$result = $this->db->find($this->fields, ['exercise_id' => $exercise_id, 'id' => $field_id], ['projection' => ['id' => 1]]);
		return count($result) > 0;
	}

	public function isFulfillmentInExercise(int $exercise_id, int $fulfillment_id): bool
	{
		$result = $this->db->find($this->fulfillments, ['exercise_id' => $exercise_id, 'id' => $fulfillment_id], ['projection' => ['id' => 1]]);
		return count($result) > 0;
	}

	public function setFieldLabel(int $id, string $label): void
	{
		$this->db->update($this->fields, ['id' => $id], ['$set' => ['label' => $label]]);
	}

    public function setFieldAnswer(int $id, string $answer): void
    {
        $this->db->update($this->fields, ['id' => $id], ['$set' => ['answer' => $answer]]);
    }

	public function setFieldKind(int $id, int $kind): void
	{
		$this->db->update($this->fields, ['id' => $id], ['$set' => ['kind' => $kind]]);
	}

	public function deleteExercise(int $id): void
	{
		$this->db->remove($this->exercises, ['id' => $id]);
	}

	public function setExerciseStatus(int $id, int $status)
	{
		$result = $this->db->update($this->exercises, ['id' => $id], ['$set' => ['status' => $status]]);
		return $result;
	}

	public function getFieldsCount(int $exercise_id): int
	{
		$result = $this->db->find($this->fields, ['exercise_id' => $exercise_id]);
		return count($result);
	}

	public function getExerciseByFieldId(int $field_id): int
	{
		$result = $this->db->find($this->fields, ['id' => $field_id], ['projection' => ['exercise_id' => 1]]);
		return $result[0]['exercise_id'];
	}

	public function getExerciseByFulfillmentId(int $fulfillment_id): int
	{
		$result = $this->db->find($this->fulfillments, ['id' => $fulfillment_id], ['projection' => ['exercise_id' => 1]]);
		return $result[0]['exercise_id'];
	}

    public function getFulfillmentDataCorrection(int $field_id, int $fulfillment_id): string
    {
        $result = $this->db->find($this->fulfillments_data, ['fulfillment_id' => $fulfillment_id, 'field_id' => $field_id], ['projection' => ['correction' => 1]]);
        return $result[0]['correction'];
    }

    public function setAnswerCorrection(int $field_id, int $fulfillment_id, int $correction): void
    {
        $this->db->update($this->fulfillments_data, ['fulfillment_id' => $fulfillment_id, 'field_id' => $field_id], ['$set' => ['correction' => $correction]]);
    }

	public function doesUserExist(int $id): bool
	{
		$result = $this->db->find($this->users, ['id' => $id], ['projection' => ['id' => 1]]);
		return count($result) > 0;
	}

	public function getUserUsername(int $id): string
	{
		$result = $this->db->find($this->users, ['id' => $id], ['projection' => ['username' => 1]]);
		return $result[0]['username'];
	}

	public function getUserRole(int $id): int
	{
		$result = $this->db->find($this->users, ['id' => $id], ['projection' => ['role' => 1]]);
		return $result[0]['role'];
	}

	public function getUsers(int $role = ALL_USER): array
	{
		return $role == ALL_USER ?
				$this->db->find($this->users, [], ['projection' => ['id' => 1]]) :
				$this->db->find($this->users, ['role' => $role], ['projection' => ['id' => 1]]);
	}

	public function deleteUser(int $userId): void
	{
		$this->db->remove($this->users, ['id' => $userId]);
	}

	public function setUserRole(int $id, int $role): void
	{
		$this->db->update($this->users, ['id' => $id], ['$set' => ['role' => $role]]);
	}

	public function findUserIdByUsername(string $username): int
	{
		$result = $this->db->find($this->users, ['username' => $username], ['projection' => ['id' => 1]]);
		return count($result) > 0 ? $result[0]['id'] : -1;
	}

	public function createUser(string $username, string $hashedPassword): int
	{
		$result = $this->db->insert($this->users, ['username' => $username, 'password' => $hashedPassword, 'role' => 0]);
		return $result[0]['id'];
	}

	public function getPassword(int $id): string
	{
		$result = $this->db->find($this->users, ['id' => $id], ['projection' => ['password' => 1]]);
		return $result[0]['password'];
	}

	public function isUserExistByUsername(string $username): bool
	{
		$result = $this->db->find($this->users, ['username' => $username], ['projection' => ['id' => 1]]);
		return count($result) > 0;
	}

	public function getExercisesByLimitDateAndIsAnswering(DateTime $date): array
	{
		$date_UTCDateTime = new MongoDB\BSON\UTCDateTime($date);
		$result = $this->db->find($this->exercises, [
			'limit_date' => ['$lte' => $date_UTCDateTime],
			'status' => 1
		], ['projection' => ['id' => 1]]);
		return $result;
	}

	public function getExerciseLimitDate(int $exceriseId): DateTime|null
	{
		$result = $this->db->find($this->exercises, ['id' => $exceriseId], ['projection' => ['limit_date' => 1]]);
		return isset($result[0]['limit_date']) ? $result[0]['limit_date']->toDateTime() : null;
	}
}
