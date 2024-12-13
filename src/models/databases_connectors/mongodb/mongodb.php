<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description mongodb connector class to select of modify the database.
 * See: "https://www.mongodb.com/docs/php-library/current/reference/class/MongoDBCollection/" for commands
 */
class MongoDB
{
	public $mongodb;
	//Need sequences for auto increment
	private array $collections = ['exercises', 'fields', 'fulfillments', 'fulfillments_data', 'sequences'];

	public function __construct($host, $port, $mongo_user, $mongo_password)
	{
		$client = new MongoDB\Client("mongodb://$mongo_user:$mongo_password@$host:$port");

		//Extract existing database names
		$arrayDBNames = [];
		foreach ($client->listDatabases() as $databaseInfo) {
			$arrayDBNames[] = $databaseInfo['name'];
		}

		//Check for if DB exist. Watch out we don't cover case of existing DB but missing collections
		if (!in_array('nosql1', $arrayDBNames)) {
			$this->createDB($client);
		}

		$this->mongodb = $client->nosql1;
	}

	/**
	 * Create database "nosql1" with needed collections with their setup
	 */
	private function createDB($client)
	{
		//Create collections
		foreach ($this->collections as $collection) {
			$client->nosql1->createCollection($collection);
		}

		//setup auto-increment
		foreach ($this->collections as $collection) {
			$client->nosql1->sequences->insertOne(['collection' => $collection, 'sequence_id' => 0]);
		}
	}

	/**
	 * increment the given collection sequence_id and then return it
	 * Doc: "https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-findOneAndUpdate/#mongodb-phpmethod-phpmethod.MongoDB-Collection--findOneAndUpdate--"
	 *
	 * @param  array MongoDB\Collection The collection to execute the request on.
	 * @return int Id for the entry of collection
	 */
	private function idIncrement($collection)
	{
		$nextId = $this->mongodb->sequences->findOneAndUpdate(
			['collection' => $collection->getCollectionName()],
			['$inc' => ['sequence_id' => 1]],
			['returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
		)->sequence_id;

		return $nextId;
	}

	/**
	 * find() MongoDB request. Will hide from documents the _id unless specified not to.
	 * Doc: "https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-find/"
	 *
	 * @param  array MongoDB\Collection The collection to execute the request on.
	 * @param  array $filter The filter criteria that specifies the documents to query. (default: [])
	 * @param  array $option An array specifying the desired options. (default: [])
	 * @return array[array] the result of the request
	 */
	public function find($collection, array $filter = [], array $option = []): array
	{
		//Remove the _id field unless specified not to
		if (isset($option['projection'])) {
			//Makes sure to not overwrite existing $option['projection'] keys
			if (!isset($option['projection']['_id'])) {
				$option['projection']['_id'] = 0;
			}
		} else {
			$option['projection'] = ['_id' => 0];
		}

		$objects_result = $collection->find($filter, $option);

		//Transform to array from object
		$array_result = [];
		foreach ($objects_result as $result) {
			$array_result[] = (array) $result;
		}

		return $array_result;
	}

	/**
	 * insertOne() MongoDB request. Equivalent to the insert().
	 * Doc: "https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-insertOne/#mongodb-phpmethod-phpmethod.MongoDB-Collection--insertOne--"
	 *
	 * @param  array MongoDB\Collection The collection to execute the request on.
	 * @param  array $document The document to insert into the collection.
	 * @param  array $option An array specifying the desired options. (default: [])
	 * @return array[array] the result of the request
	 */
	public function insert($collection, array $document, array $option = []): array
	{
		//InsertMany() was skipped to avoid trouble with nested array (list vs associative)
		//Get Next IdCould create a js fonction but this is simpler to setup
		$document['id'] = $this->idIncrement($collection);

		$resultObject = $collection->insertOne($document, $option)->getInsertedId();

		$filter = ['_id' => $resultObject];

		return $this->find($collection, $filter);
	}

	/**
	 * updateMany() MongoDB request. Equivalent to the update()
	 * Doc: "https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-updateMany/"
	 *
	 * @param  array MongoDB\Collection The collection to execute the request on.
	 * @param  array $filter The filter criteria that specifies the documents to update.
	 * @param  array $update Specifies the field and value combinations to update and any relevant update operators
	 * @param  array $option An array specifying the desired options. (default: [])
	 * @return array[array] the result of the request
	 */
	public function update($collection, array $filter, array $update, array $option = []): array
	{
		$collection->updateMany($filter, $update, $option);

		//If needed in futur we can use the result.
		//See: https://www.php.net/class.mongodb-driver-writeresult

		return $this->find($collection, $filter);
	}

	/**
	 * deleteMany() MongoDB request. Equivalent to the remove()
	 * Doc: "https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-deleteMany/#mongodb-phpmethod-phpmethod.MongoDB-Collection--deleteMany--"
	 *
	 * @param  array MongoDB\Collection The collection to execute the request on.
	 * @param  array $filter The filter criteria that specifies the documents to delete.
	 * @param  array $option An array specifying the desired options. (default: [])
	 */
	public function remove($collection, array $filter, array $option = []): void
	{
		$collection->deleteMany($filter, $option);

		//If needed in futur we can use the result.
		//See: https://www.php.net/class.mongodb-driver-writeresult
	}
}
