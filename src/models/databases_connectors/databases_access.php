<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description  Database acces implementation if we need to change the database just implement this class that will return the result of for example sql request
 */
define('ALL_EXERCISES', -1);
define('ALL_USER', -1);

/**
 * Interface DatabasesAccess
 *
 * Provides an interface for accessing and manipulating exercises, fields, and fulfillments in a database.
 */
interface DatabasesAccess
{
	/**
	 * Checks if an exercise exists by its ID.
	 *
	 * @param int $id The ID of the exercise.
	 * @return bool True if the exercise exists, false otherwise.
	 */
	public function doesExerciseExist(int $id): bool;

	/**
	 * Creates a new exercise with the given title.
	 *
	 * @param string $title The title of the exercise.
	 * @param DateTime|null $limitDate The limit date of the exercise.
	 * @return int The ID of the newly created exercise.
	 */
	public function createExercise(string $title, DateTime $limitDate): int;

	/**
	 * Retrieves the title of an exercise by its ID.
	 *
	 * @param int $id The ID of the exercise.
	 * @return string The title of the exercise.
	 */
	public function getExerciseTitle(int $id): string;

	/**
	 * Retrieves a list of exercises, optionally filtered by status.
	 *
	 * @param int $status The status to filter exercises by (default is ALL_EXERCISES).
	 * @return array An array of exercises.
	 */
	public function getExercises(int $status = ALL_EXERCISES): array;

	/**
	 * Retrieves the fields associated with a specific exercise.
	 *
	 * @param int $exercise_id The ID of the exercise.
	 * @return array An array of fields.
	 */
	public function getFields(int $exercise_id): array;

	/**
	 * Checks if a field exists by its ID.
	 *
	 * @param int $id The ID of the field.
	 * @return bool True if the field exists, false otherwise.
	 */
	public function doesFieldExist(int $id): bool;

	/**
	 * Checks if a fulfillment exists by its ID.
	 *
	 * @param int $id The ID of the fulfillment.
	 * @return bool True if the fulfillment exists, false otherwise.
	 */
	public function doesFulfillmentExist(int $id): bool;

	/**
	 * Retrieves the fields associated with a specific fulfillment.
	 *
	 * @param int $id The ID of the fulfillment.
	 * @return array An array of fields.
	 */
	public function getFulfillmentFields(int $id): array;

	/**
	 * Retrieves the body content of a specific fulfillment field.
	 *
	 * @param int $field_id The ID of the field.
	 * @param int $fulfillment_id The ID of the fulfillment.
	 * @return string The body content of the fulfillment field.
	 */
	public function getFulfillmentBody(int $field_id, int $fulfillment_id): string;

	/**
	 * Retrieves the timestamp of a specific fulfillment.
	 *
	 * @param int $id The ID of the fulfillment.
	 * @return DateTime The timestamp of the fulfillment.
	 */
	public function getFulfillmentTimestamp(int $id): DateTime;

	/**
	 * Sets the body content of a specific fulfillment field.
	 *
	 * @param int $field_id The ID of the field.
	 * @param int $fulfillment_id The ID of the fulfillment.
	 * @param string $body The body content to set.
	 */
	public function setFulfillmentBody(int $field_id, int $fulfillment_id, string $body): void;

	/**
	 * Creates a new fulfillment for a specific exercise.
	 *
	 * @param int $exercise_id The ID of the exercise.
	 * @return int The ID of the newly created fulfillment.
	 */
	public function createFulfillment(int $exercise_id): int;

	/**
	 * Retrieves the fulfillments associated with a specific exercise.
	 *
	 * @param int $exercise_id The ID of the exercise.
	 * @return mixed The fulfillments associated with the exercise.
	 */
	public function getFulfillments(int $exercise_id);

	/**
	 * Creates a new fulfillment field with the given body content.
	 *
	 * @param int $field_id The ID of the field.
	 * @param int $fulfillment_id The ID of the fulfillment.
	 * @param string $body The body content to set.
	 */
	public function createFulfillmentField(int $field_id, int $fulfillment_id, string $body): void;

	/**
	 * Retrieves the label of a specific field.
	 *
	 * @param int $id The ID of the field.
	 * @return string The label of the field.
	 */
	public function getFieldLabel(int $id): string;

	/**
	 * Retrieves the kind of a specific field.
	 *
	 * @param int $id The ID of the field.
	 * @return int The kind of the field.
	 */
	public function getFieldAnswer(int $id): string;

	/**
	 * Retrieves the kind of a specific field.
	 *
	 * @param int $id The ID of the field.
	 * @return int The kind of the field.
	 */
	public function getFieldKind(int $id): int;

	/**
	 * Creates a new field for a specific exercise.
	 *
	 * @param int $exercise_id The ID of the exercise.
	 * @param string $label The label of the field.
	 * @param string $answer The answer og the field.
	 * @param int $kind The kind of the field.
	 * @return int The ID of the newly created field.
	 */
	public function createField(int $exercise_id, string $label, string $answer, int $kind): int;

	/**
	 * Deletes a specific field by its ID.
	 *
	 * @param int $id The ID of the field.
	 */
	public function deleteField(int $id): void;

	/**
	 * Checks if a field is associated with a specific exercise.
	 *
	 * @param int $exercise_id The ID of the exercise.
	 * @param int $field_id The ID of the field.
	 * @return bool True if the field is in the exercise, false otherwise.
	 */
	public function isFieldInExercise(int $exercise_id, int $field_id): bool;

	/**
	 * Checks if a fulfillment is associated with a specific exercise.
	 *
	 * @param int $exercise_id The ID of the exercise.
	 * @param int $fulfillment_id The ID of the fulfillment.
	 * @return bool True if the fulfillment is in the exercise, false otherwise.
	 */
	public function isFulfillmentInExercise(int $exercise_id, int $fulfillment_id): bool;

	/**
	 * Sets the label of a specific field.
	 *
	 * @param int $id The ID of the field.
	 * @param string $label The label to set.
	 */
	public function setFieldLabel(int $id, string $label): void;

	/**
	 * Sets the kind of a specific field.
	 *
	 * @param int $id The ID of the field.
	 * @param int $answer The kind to set.
	 */
	public function setFieldAnswer(int $id, string $answer): void;

	/**
	 * Sets the kind of a specific field.
	 *
	 * @param int $id The ID of the field.
	 * @param int $kind The kind to set.
	 */
	public function setFieldKind(int $id, int $kind): void;

	/**
	 * Deletes a specific exercise by its ID.
	 *
	 * @param int $id The ID of the exercise.
	 */
	public function deleteExercise(int $id): void;

	/**
	 * Retrieves the status of a specific exercise.
	 *
	 * @param int $id The ID of the exercise.
	 * @return int The status of the exercise.
	 */
	public function getExerciseStatus(int $id): int;

	/**
	 * Sets the status of a specific exercise.
	 *
	 * @param int $id The ID of the exercise.
	 * @param int $status The status to set.
	 */
	public function setExerciseStatus(int $id, int $status);

	/**
	 * Retrieves the count of fields associated with a specific exercise.
	 *
	 * @param int $exercise_id The ID of the exercise.
	 * @return int The count of fields.
	 */
	public function getFieldsCount(int $exercise_id): int;

	/**
	 * Retrieves the exercise ID associated with a specific field ID.
	 *
	 * @param int $field_id The ID of the field.
	 * @return int The ID of the exercise.
	 */
	public function getExerciseByFieldId(int $field_id): int;

	/**
	 * Retrieves the exercise ID associated with a specific fulfillment ID.
	 *
	 * @param int $fulfillment_id The ID of the fulfillment.
	 * @return int The ID of the exercise.
	 */
	public function getExerciseByFulfillmentId(int $fulfillment_id): int;

    /**
     * Retrieves the correction value of a specific fulfillment field.
     *
     * @param int $field_id The ID of the field.
     * @param int $fulfillment_id The ID of the fulfillment.
     * @return int The correction value of the fulfillment.
     */
    public function getFulfillmentDataCorrection(int $field_id, int $fulfillment_id): string;

    /**
     * Sets the correction value of a specific Answer.
     *
     * @param int $field_id The ID of the Field.
     * @param int $fulfillment_id The ID of the Answer.
     * @param int $correction The Correction value to set.
     */
    public function setAnswerCorrection(int $field_id, int $fulfillment_id, int $correction);

	/**
	 * Retrieves the user ID associated with a specific fulfillment ID.
	 *
	 * @param int $fulfillment_id The ID of the fulfillment.
	 *
	 * @return int The ID of the user.
	 */
	public function doesUserExist(int $id): bool;

	/**
	 * Retrieves the username of a specific user.
	 *
	 * @param int $id The ID of the user.
	 * @return string The username of the user.
	 */
	public function getUserUsername(int $id): string;

	/**
	 * Retrieves the role of a specific user.
	 *
	 * @param int $id The ID of the user.
	 * @return int The role of the user.
	 */
	public function getUserRole(int $id): int;

	/**
	 * Retrieves all users.
	 *
	 * @param int $role The role to filter users by (default is ALL_USER).
	 * @return array[array[string|int,int]] An array of users. Each user is represented by an array containing the user's ID.
	 */
	public function getUsers(int $role = ALL_USER): array;

	/**
	 * Creates a new user with the given username and role.
	 *
	 * @param string $username The username of the user.
	 * @param int $role The role of the user.
	 */
	public function deleteUser(int $userId): void;

	/**
	 * Sets the role of a specific user.
	 *
	 * @param int $id The ID of the user.
	 * @param int $role The role to set.
	 */
	public function setUserRole(int $id, int $role): void;

	/**
	 * Retrieves the id of a specific user
	 *
	 * @param string $username The username of the user.
	 * @return int The id of the user.
	 */
	public function findUserIdByUsername(string $username): int;

	/**
	 * Create a new user.
	 *
	 * @param string $username The username of the user.
	 * @param string $hashedPassword The password but hashed of the user
	 * @return int The ID of the user.
	 */
	public function createUser(string $username, string $hashedPassword): int;

	/**
	 * Retrieve the hashed password of a specific user.
	 *
	 * @param int $id The ID of the user.
	 * @return string The password but hashed of the user
	 */
	public function getPassword(int $id): string;

	/**
	 * Check if user exists by username
	 *
	 * @param int $username username of the user
	 * @return bool if the user already exist or not
	 */
	public function isUserExistByUsername(string $username): bool;

	/**
	 * Retrieves a list of exercises created before a specific date.
	 *
	 * @param int $exceriseId The ID of the exercise.
	 * @return DateTime|null An array of exercises.
	 */
	public function getExerciseLimitDate(int $exceriseId): DateTime|null;

	/**
	 * Retrieves a list of exercises created between two specific dates and is in answering mode.
	 *
	 * @param DateTime $date The date to filter exercises by.
	 * @return array An array of exercises.
	 */
	public function getExercisesByLimitDateAndIsAnswering(DateTime $date): array;
}
