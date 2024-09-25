<?php

interface DatabasesAccess
{
	public function doesExcerciceExist(int $id): bool;

	public function createExercice(string $title): int;

	public function getExcerciceTitle(int $id): string;
}
