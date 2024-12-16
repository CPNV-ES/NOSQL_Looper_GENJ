<?php

/**
 * @author Ethann Schneider
 * @version 29.11.2024
 * @description  User class
 */

require_once MODEL_DIR . '/databases_connectors/databases_choose.php';

enum Role: int
{
	case Student = 0;
	case Teacher = 1;
	case Dean = 2;

	public static function fromName(string $name): Role
	{
		switch ($name) {
			case 'student':
				return Role::Student;
				// no break
			case 'teacher':
				return Role::Teacher;
			case 'dean':
				return Role::Dean;
			default:
				throw new RoleNotFoundException();
		}
	}
}

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
	 * Get all users
	 *
	 * @return array[User] an array of all users
	 */
	public static function all(): array
	{
		$users = [];
		foreach ((new DatabasesChoose())->getDatabase()->getUsers() as $user) {
			$users[] = new User($user['id']);
		}
		return $users;
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
	 * Get user role
	 *
	 * @return Role the role of the user
	 */
	public function getRole(): Role
	{
		return Role::from($this->database_access->getUserRole($this->id));
	}

	/**
	 * Set the role of the user
	 *
	 * @param Role $role the role to set
	 *
	 * @return void
	 */
	public function setRole(Role $role): void
	{
		$this->database_access->setUserRole($this->id, $role->value);
	}

	/**
	 * Delete the user
	 *
	 * @return void
	 */
	public function delete(): void
	{
		$this->database_access->deleteUser($this->id);
	}
}

class UserNotFoundException extends LooperException
{
	public function __construct()
	{
		parent::__construct(404, 'User not found');
	}
}

class RoleNotFoundException extends LooperException
{
	public function __construct()
	{
		parent::__construct(404, 'Role not found');
	}
}
