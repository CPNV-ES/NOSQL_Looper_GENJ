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
	}

	public function doesExerciseExist(int $id): bool
	{
		$result = $this->db->find($this->exercises, ['id' => $id]);
		return count($result) > 0;
	}

	public function createExercise(string $title): int
	{
		$result = $this->db->insert($this->exercises, ['title' => $title, 'status' => 0]);
		return $result[0]['id'];
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
			$result = $this->db->find($this->exercises, [], ['projection' => ['id' => $id]]);
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

	public function getFulfillmentTimestamp(int $id)
	{
		$result = $this->db->find($this->fulfillments, ['id' => $id], ['projection' => ['creation_date' => 1]]);
		//Convert here since og db directly returned string
		return $result[0]['creation_date']->toDateTime()->format('Y-m-d H:i:s.u');
	}

	public function setFulfillmentBody(int $field_id, int $fulfillment_id, string $body): void //array
	{
		$result = $this->db->update($this->fulfillments_data, ['fulfillment_id' => $fulfillment_id, 'field_id' => $field_id], ['$set' => ['body' => $body]]);
		//return $result;
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

	public function createFulfillmentField(int $field_id, int $fulfillment_id, string $body): void //array
	{
		$result = $this->db->insert($this->fulfillments_data, ['field_id' => $field_id, 'fulfillment_id' => $fulfillment_id, 'body' => $body]);
		//return $result;
	}

	public function getFieldLabel(int $id): string
	{
		$result = $this->db->find($this->fields, ['id' => $id], ['projection' => ['label' => 1]]);
		return $result[0]['label'];
	}

	public function getFieldKind(int $id): int
	{
		$result = $this->db->find($this->fields, ['id' => $id], ['projection' => ['kind' => 1]]);
		return $result[0]['kind'];
	}

	public function createField(int $exercise_id, string $label, int $kind): int
	{
		$result = $this->db->insert($this->fields, ['label' => $label, 'kind' => $kind, 'exercise_id' => $exercise_id]);
		return $result[0]['id'];
	}

	public function deleteField(int $id): void
	{
		$result = $this->db->remove($this->fields, ['id' => $id]);
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

	public function setFieldLabel(int $id, string $label): void //array
	{
		$result = $this->db->update($this->fields, ['id' => $id], ['$set' => ['label' => $label]]);
		//return $result;
	}

	public function setFieldKind(int $id, int $kind): void //array
	{
		$result = $this->db->update($this->fields, ['id' => $id], ['$set' => ['kind' => $kind]]);
		//return $result;
	}

	public function deleteExercise(int $id): void //array
	{
		$result = $this->db->remove($this->exercises, ['id' => $id]);
		//return $result;
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
}
