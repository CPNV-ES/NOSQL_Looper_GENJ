<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description This file contains the fulfillment controller, which handles fulfillment actions.
 */

include_once MODEL_DIR . '/exercise.php';
include_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/databases_connectors/databases_choose.php';

/**
 * FulfillmentController
 * This controller class handles actions related to the creation and editing of fulfillments.
 */
class UserController
{

    private DatabasesAccess $database_access;

    public function lougout()
    {
        session_destroy();
        include VIEW_DIR . '/home.php';
    }

    public function login()
    {

        $userid = User::FindUserId($_POST['user_username']);
        $hashedPassword = User::getHashedPassword($userid);
        //TODO : check password and log in

        if (HashedPassword::fromNonHashed($_POST['user_password']) == $hashedPassword) {
            //TODO : exception password didn't match account's password
            include VIEW_DIR . '/login.php';
        } else {

            $_SESSION['user'] == $userid; //TODO replace this by  username

            include VIEW_DIR . '/home.php';
        }
    }

    public function register()
    {
        //TODO : create user an then log in
        $user = new User(User::findUserId($_POST['user_username']));
        if ($user) {
            include VIEW_DIR . '/register.php';
            //TODO : exception username already in use
        }

        $passwordhashed = HashedPassword::fromNonHashed($_POST['user_password']);

        $userid = User::CreateUser($_POST['user_username'], $passwordhashed);

        $_SESSION['user'] == $userid;

        include VIEW_DIR . '/home.php';
    }
}
