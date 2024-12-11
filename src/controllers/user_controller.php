<?php

/**
 * @author Ethann Schneider, Geoffroy  Wildi, Jomana Kaempf, Nathan Chauveau
 * @version 11.12.2024
 * @description This file contains the user controller, which handles user actions.
 */

require_once MODEL_DIR . '/user.php';

/**
 * UserController
 * This controller class handles actions related to the user connection.
 */
class UserController
{


    /**
     * Display the login page and log the user in.
     *
     * @return void
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user = User::FindUser($_POST['user_username']);

            include VIEW_DIR . '/home.php';
        } else {
            include VIEW_DIR . '/login.php';
        }
    }

    /**
     * Display the login page.
     *
     * @return void
     */
    public function register()
    {
        include VIEW_DIR . '/register.php';
    }
}
