<?php

/**
 * @author Ethann Schneider
 * @version 15.12.2024
 * @description This file contains the HashedPassword class
 */

class HashedPassword
{
	private string $hashed_password;

	/**
	 * @description This function is the constructor of the HashedPassword class
	 * @param string $hashed_password The password to hash
	 */
	private function __construct(string $hashed_password)
	{
		$this->hashed_password = $hashed_password;
	}

	/**
	 * @description This function hashes the password and returns a HashedPassword object
	 * @param string $password The password to hash
	 * @return HashedPassword The hashed password
	 */
	public static function fromNonHashed(string $password): HashedPassword
	{
		return new HashedPassword(self::hashPassword($password));
	}

	/**
	 * @description This function creates a HashedPassword object from a hashed password
	 * @param string $hashed_password The hashed password
	 * @return HashedPassword The hashed password
	 */
	public static function fromHashed(string $hashed_password): HashedPassword
	{
		return new HashedPassword($hashed_password);
	}

	/**
	 * @description This function returns the hashed password
	 * @return string The hashed password
	 */
	public function value(): string
	{
		return $this->hashed_password;
	}

	/**
	 * @description This function verify if the password is correct
	 * @param string $password The password to verify
	 * @return bool True if the password is correct, false otherwise
	 */
	public function verify(string $password): bool
	{
		return $this->hashed_password === self::hashPassword($password);
	}

	private static function hashPassword(string $password): string
	{
		return hash('sha256', $password . $_ENV['PASSWORD_SALT']);
	}
}
