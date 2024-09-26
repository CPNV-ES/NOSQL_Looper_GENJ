<?php

require MODEL_DIR . '/databases_connectors/postgresql/postgresql_access.php';

// This class serves as a hub for managing multiple databases, allowing for easy addition or removal of database systems as needed.
class DatabasesChoose
{
	// static for now but should be in a dynamic config
	private string $databases = 'postgresql';

	public function __construct()
	{
	}

	public function getDatabase(): DatabasesAccess
	{
		switch ($this->databases) {
			default:
				return new PostgresqlAccess('postgresql', 5432, 'db_looper', $_ENV['POSTGRES_USER'], $_ENV['POSTGRES_PASSWORD']);
		}
	}
}
