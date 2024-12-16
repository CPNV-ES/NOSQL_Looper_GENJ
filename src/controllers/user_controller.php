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
	public function deleteUser(int $user_id)
	{
		$user = new User($user_id);
		$user->delete();
		header('Location: /users');
	}

	public function editUser(int $user_id)
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
