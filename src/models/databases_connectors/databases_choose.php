<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description  Database choose file is set to choose the correct databases based on a config (currently there is only one)
 */

require MODEL_DIR . '/databases_connectors/postgresql/postgresql_access.php';
require MODEL_DIR . '/databases_connectors/mongodb/mongodb_access.php';

/**
 * This class serves as a hub for managing multiple databases, allowing for easy addition or removal of database systems as needed.
 */
class DatabasesChoose
{
	// static for now but should be in a dynamic config
	private string $databases = '';
	private static DatabasesAccess $database;

	/**
	 * DatabasesChoose contructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->databases = $_ENV['DATABASES'] ?? 'postgresql';
		if (isset(self::$database)) {
			return;
		}
		switch ($this->databases) {
			case 'mongodb':
				self::$database = new MongodbAccess('mongodb', 27017, 'db_looper', $_ENV['MONGO_INITDB_ROOT_USERNAME'], $_ENV['MONGO_INITDB_ROOT_PASSWORD']);
				break;
			default:
				self::$database = new PostgresqlAccess('postgresql', 5432, 'db_looper', $_ENV['POSTGRES_USER'], $_ENV['POSTGRES_PASSWORD']);
		}
	}

	/**
	 * get dataases access implement with the correct databases
	 *
	 * @return DatabasesAccess the database access
	 */
	public function getDatabase(): DatabasesAccess
	{
		return self::$database;
	}
}
