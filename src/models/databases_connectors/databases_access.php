<?php

interface DatabasesAccess
{
	public function doesExerciseExist(int $id): bool;

	public function createExercise(string $title): int;

	public function getExerciseTitle(int $id): string;

	public function getExercises(int $status = -1): array;

	public function deleteExercise(int $id): void;

	public function getExerciseStatus(int $id): string;
}
