<?php

/**
 * @author Ethann Schneider
 * @version 29.11.2024
 * @description  User class
 */

require_once MODEL_DIR . '/databases_connectors/databases_choose.php';

/**
 * This class is the user buiness logic of the application
 */
class User
{
	private DatabasesAccess $database_access;
	private int $id;

	/**
	 * constructor of the user
	 *
	 * @param  int $id the id of the user
	 *
	 * @throws UserNotFoundException if the user does not exist
	 * @return void
	 */
	public function __construct(int $id)
	{
		$this->id = $id;

		$this->database_access = (new DatabasesChoose())->getDatabase();

		if (!$this->database_access->doesUserExist($id)) {
			throw new UserNotFoundException();
		}
	}

	/**
	 * Get the id of the user
	 *
	 * @return int the id of the user
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the name of the user
	 *
	 * @return string the name of the user
	 */
	public function getUsername(): string
	{
		return $this->database_access->getUserUsername($this->id);
	}

	/**
	 * Get the user ID based on the given username
	 *
	 * @return User The user;
	 */
	public static function byUsername($username): User
	{
		$database_access = (new DatabasesChoose())->getDatabase();

		return new User($database_access->findUserIdByUsername($username));
	}

	/**
	 * Create the user and return his ID
	 *
	 * @return int ID of the user;
	 */
	public static function create($username, $password)
	{
		$database_access = (new DatabasesChoose())->getDatabase();

		return $database_access->createUser($username, $password);
		;
	}

	/**
	 * Get the user's password base on the given ID
	 *
	 * @return HashedPassword password of the user;
	 */
	public function getPassword(): HashedPassword
	{
		return HashedPassword::fromHashed($this->database_access->getPassword($this->id));
	}
}

class UserNotFoundException extends LooperException
{
	public function __construct()
	{
		parent::__construct(404, 'User not found');
	}
}
