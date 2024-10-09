<?php

interface DatabasesAccess
{
	public function doesExerciseExist(int $id): bool;

	public function createExercise(string $title): int;

	public function getExerciseTitle(int $id): string;

	public function getExercises(int $status = -1): array;

	public function getExerciseStatus(int $id = -1): Status;

	public function setExerciseStatus(int $id, Status $status);

	public function getFieldsCount(int $exercise_id): int;
}
