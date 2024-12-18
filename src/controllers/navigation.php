<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description  This is the navigation controller that every route redirect to a view
 */

include_once MODEL_DIR . '/exercise.php';
include_once MODEL_DIR . '/user.php';

/**
 * Navigation Controller
 * This is the controller responsible for navigating to various views related to exercises,
 * fulfillments, and fields.
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description  This is the navigation controller that every route redirect to a view
 */
class Navigation
{
	/**
	 * Display the home page.
	 *
	 * @return void
	 */
	public function home(User $user)
	{
		include VIEW_DIR . '/home.php';
	}

	/**
	 * Display the create an exercise page.
	 *
	 * @return void
	 */
	public function createAnExercises(User $teacher)
	{
		include VIEW_DIR . '/create_an_exercise.php';
	}

	/**
	 * Display the take an exercise page.
	 *
	 * @return void
	 */
	public function takeAnExercises(User $user)
	{
		$exercises = Exercise::getExercises(Status::Answering);
		include VIEW_DIR . '/take_an_exercise.php';
	}

	/**
	 * Display the manage exercises page.
	 *
	 * @return void
	 */
	public function manageExercises(User $teacher)
	{
		$buildingExercises = Exercise::getExercises(Status::Building);
		$answeringExercises = Exercise::getExercises(Status::Answering);
		$closeExercises = Exercise::getExercises(Status::Closed);

		include VIEW_DIR . '/manage_an_exercise.php';
	}

	/**
	 * Display the manage fields page for a specific exercise.
	 *
	 * @param  mixed $id The ID of the exercise.
	 * @return void
	 */
	public function manageField(User $teacher, int $id)
	{
		$exercise = new Exercise($id);
		$fields = $exercise->getFields();

		if ($exercise->getStatus() != Status::Building) {
			unauthorized();
			return;
		}

		include VIEW_DIR . '/manage_field.php';
	}

	/**
		 * Display the edit field page.
		 *
		 * @param int $exercise_id The ID of the exercise.
		 * @param int $id The ID of the field to edit.
		 * @return void
		 */
	public function editAField(User $teacher, int $exercise_id, int $id)
	{
		$exercise = new Exercise($exercise_id);
		$field = new Field($id);

		if (!$exercise->isFieldInExercise($field)) {
			lost();
			return;
		}

		if ($exercise->getStatus() != Status::Building) {
			unauthorized();
			return;
		}

		include VIEW_DIR . '/edit_a_field.php';
	}

	/**
	 * Display the take page for answering an exercise.
	 *
	 * @param  int $exercise_id The ID of the exercise.
	 * @return void
	 */
	public function take(User $user,int $exercise_id)
	{
		$edit_take = false;
		$exercise = new Exercise($exercise_id);

		$fields = $exercise->getFields();

		if ($exercise->getStatus() != Status::Answering) {
			unauthorized();
			return;
		}

		include VIEW_DIR . '/take.php';
	}

	/**
	 * Display the results of an exercise.
	 *
	 * @param $exercise_id The ID of the exercise.
	 * @return void
	 */
	public function showResults(User $teacher, int $id)
	{
		$exercise = new Exercise($id);
		include VIEW_DIR . '/show_exercise_results.php';
	}

	/**
	 * Display the results of a specific field in an exercise.
	 *
	 * @param  int $exercise_id The ID of the exercise.
	 * @param  int $field_id The ID of the field.
	 * @return void
	 */
	public function showFieldResults(User $teacher, int $exercise_id, int $field_id)
	{
		$exercise = new Exercise($exercise_id);
		$field = new Field($field_id);

		if (!$exercise->isFieldInExercise($field)) {
			lost();
			return;
		}

		include VIEW_DIR . '/show_field_results.php';
	}

	/**
	 * Display the results of a fulfillment.
	 *
	 * @param  int $exercise_id The ID of the exercise.
	 * @param  int $fulfillment_id The ID of the fulfillment.
	 * @return void
	 */
	public function showFulfillmentResults(User $teacher, int $exercise_id, int $fulfillment_id): void
	{
		$exercise = new Exercise($exercise_id);

		$fulfillment = new Fulfillment($fulfillment_id);

		if (!$exercise->isFulfillmentInExercise($fulfillment)) {
			lost();
			return;
		}

		include VIEW_DIR . '/show_fulfillment_results.php';
	}

	/**
	 * Display the edit fulfillment page.
	 *
	 * @param  int $exercise_id The ID of the exercise.
	 * @param  int $fulfillment_id The ID of the fulfillment to be edited.
	 * @return void
	 */
	public function editFulfillment(User $user, int $exercise_id, int $fulfillment_id)
	{
		$exercise = new Exercise($exercise_id);
		$fulfillment = new Fulfillment($fulfillment_id);

		$fields = $fulfillment->getFields();

		if (!$exercise->isFulfillmentInExercise($fulfillment)) {
			lost();
			return;
		}

		if ($exercise->getStatus() != Status::Answering) {
			unauthorized();
			return;
		}

		include VIEW_DIR . '/take.php';
	}

	/**
	 * Display the manage users page.
	 *
	 * @return void
	 */
	public function manageUsers(User $dean)
	{
		$users = User::all();
		include VIEW_DIR . '/manage_users.php';
	}

	/**
	 * Display the manage single user page.
	 *
	 * @return void
	 */
	public function manageSingleUser(User $dean, int $userId)
	{
		$user = new User($userId);
		include VIEW_DIR . '/manage_single_user.php';
	}
}
