<?php

/**
 * @author Ethann Schneider
 * @version 16.12.2024
 * @description This file contains the users controller, which handles users admin actions.
 */

include_once MODEL_DIR . '/user.php';

/**
 * UserController
 * This controller class handles actions related to the management of users.
 */
class UserController
{
	/**
	 * This function is to delete an user
	 *
	 * @param User $dean current authenticated user with dean role 
	 * @param int $user_id The user to delete
	 */
	public function deleteUser(User $dean, int $user_id)
	{
		$user = new User($user_id);
		$user->delete();
		header('Location: /users');
	}

	/**
	 * This function is to edit an user role 
	 *
	 * @param User $dean current authenticated user with dean role 
	 * @param int $user_id The user to edit
	 */
	public function editUser(User $dean, int $user_id)
	{
		$user = new User($user_id);

		if (!isset($_POST['role'])) {
			header('Location: /users/' . $user_id);
			return;
		}

		$user->setRole(Role::fromName($_POST['role']));

		header('Location: /users');
	}
}
