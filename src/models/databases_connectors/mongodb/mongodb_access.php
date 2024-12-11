<?php

/**
 * @version 29.11.2024
 * @description MongodbAccess class implementing DatabasesAccess
 */

require_once MODEL_DIR . '/databases_connectors/databases_access.php';

/**
 * Class MongodbAccess
 *
 * Provides an implementation of DatabasesAccess for MongoDB databases.
 */
class MongodbAccess implements DatabasesAccess
{
	private $client;
	private $mongodb;

	/**
	 * MongodbAccess constructor.
	 *
	 * @param string $host The host of the MongoDB server.
	 * @param int $port The port of the MongoDB server.
	 * @param string $dbname The name of the database.
	 * @param string $mongo_user The username for the MongoDB server.
	 * @param string $mongo_password The password for the MongoDB server.
	 */
	public function __construct($host, $port, $dbname, $mongo_user, $mongo_password)
	{
		$this->client = new MongoDB\Client("mongodb://$mongo_user:$mongo_password@$host:$port");
		$this->mongodb = $this->client->$dbname;
	}

	public function doesExerciseExist(int $id): bool
	{
		return $this->mongodb->exercises->countDocuments(['id' => $id]) > 0;
	}

	public function createExercise(string $title): int
	{
		$result = $this->mongodb->exercises->insertOne(['title' => $title]);
		return $result->getInsertedId();
	}

	public function getExerciseTitle(int $id): string
	{
		$result = $this->mongodb->exercises->findOne(['id' => $id]);
		return $result['title'];
	}

	public function getExerciseStatus(int $id): int
	{
		$result = $this->mongodb->findOne('exercises', ['id' => $id]);
		return $result['status'];
	}

	public function getExercises(int $status = ALL_EXERCISES): array
	{
		if ($status == ALL_EXERCISES) {
			return $this->mongodb->exercises->find()->toArray();
		}
		return $this->mongodb->exercises->find(['status' => $status])->toArray();
	}

	public function getFields(int $exercise_id): array
	{
		return $this->mongodb->fields->find(['exercise_id' => $exercise_id])->toArray();
	}

	public function doesFieldExist(int $id): bool
	{
		return $this->mongodb->fields->countDocuments(['id' => $id]) > 0;
	}

	public function doesFulfillmentExist(int $id): bool
	{
		return $this->mongodb->fulfillments->countDocuments(['id' => $id]) > 0;
	}

	public function getFulfillmentFields(int $id): array
	{
		return $this->mongodb->fulfillments_data->find(['fulfillment_id' => $id])->toArray();
	}

	public function getFulfillmentBody(int $field_id, int $fulfillment_id): string
	{
		$result = $this->mongodb->fulfillments_data->findOne(['fulfillment_id' => $fulfillment_id, 'field_id' => $field_id]);
		return $result['body'];
	}

	public function getFulfillmentTimestamp(int $id)
	{
		$result = $this->mongodb->fulfillments->findOne(['id' => $id]);
		return $result['creation_date'];
	}

	public function setFulfillmentBody(int $field_id, int $fulfillment_id, string $body): void
	{
		$this->mongodb->fulfillments_data->updateOne(['fulfillment_id' => $fulfillment_id, 'field_id' => $field_id], ['$set' => ['body' => $body]]);
	}

	public function createFulfillment(int $exercise_id): int
	{
		$result = $this->mongodb->fulfillments->insertOne(['exercise_id' => $exercise_id]);
		return $result->getInsertedId();
	}

	public function getFulfillments(int $exercise_id)
	{
		return $this->mongodb->fulfillments->find(['exercise_id' => $exercise_id], ['sort' => ['creation_date' => 1]])->toArray();
	}

	public function createFulfillmentField(int $field_id, int $fulfillment_id, string $body): void
	{
		$this->mongodb->fulfillments_data->insertOne(['field_id' => $field_id, 'fulfillment_id' => $fulfillment_id, 'body' => $body]);
	}

	public function getFieldLabel(int $id): string
	{
		$result = $this->mongodb->fields->findOne(['id' => $id]);
		return $result['label'];
	}

	public function getFieldKind(int $id): int
	{
		$result = $this->mongodb->findOne('fields', ['id' => $id]);
		return $result['kind'];
	}

	public function createField(int $exercise_id, string $label, int $kind): int
	{
		$result = $this->mongodb->fields->insertOne(['label' => $label, 'kind' => $kind, 'exercise_id' => $exercise_id]);
		return $result->getInsertedId();
	}

	public function deleteField(int $id): void
	{
		$this->mongodb->fields->deleteOne(['id' => $id]);
	}

	public function isFieldInExercise(int $exercise_id, int $field_id): bool
	{
		return $this->mongodb->fields->countDocuments(['exercise_id' => $exercise_id, 'id' => $field_id]) > 0;
	}

	public function isFulfillmentInExercise(int $exercise_id, int $fulfillment_id): bool
	{
		return $this->mongodb->fulfillments->countDocuments(['exercise_id' => $exercise_id, 'id' => $fulfillment_id]) > 0;
	}

	public function setFieldLabel(int $id, string $label): void
	{
		$this->mongodb->fields->updateOne(['id' => $id], ['$set' => ['label' => $label]]);
	}

	public function setFieldKind(int $id, int $kind): void
	{
		$this->mongodb->fields->updateOne(['id' => $id], ['$set' => ['kind' => $kind]]);
	}

	public function deleteExercise(int $id): void
	{
		$this->mongodb->exercises->deleteOne(['id' => $id]);
	}

	public function setExerciseStatus(int $id, int $status)
	{
		$this->mongodb->exercises->updateOne(['id' => $id], ['$set' => ['status' => $status]]);
	}

	public function getFieldsCount(int $exercise_id): int
	{
		return $this->mongodb->fields->countDocuments(['exercise_id' => $exercise_id]);
	}

	public function getExerciseByFieldId(int $field_id): int
	{
		$result = $this->mongodb->fields->findOne(['id' => $field_id]);
		return $result['exercise_id'];
	}

	public function getExerciseByFulfillmentId(int $fulfillment_id): int
	{
		$result = $this->mongodb->fulfillments->findOne(['id' => $fulfillment_id]);
		return $result['exercise_id'];
	}
}
