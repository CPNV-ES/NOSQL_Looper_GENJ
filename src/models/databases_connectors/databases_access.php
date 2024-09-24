<?php

interface DatabasesAccess
{
	public function isExcerciceExist(int $id): bool;

	public function createExercice(string $title): int;

	public function getExcerciceTitleFromId(int $id): string;
}
