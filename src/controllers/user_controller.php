<?php

/**
 * @author Ethann Schneider, Guillaume Aubert, Jomana Kaempf
 * @version 29.11.2024
 * @description This file contains the fulfillment controller, which handles fulfillment actions.
 */

include_once MODEL_DIR . '/exercise.php';
include_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/hashedPassword.php';
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
        if (!isset($_POST['user_username'], $_POST['user_password'])){
            badRequest();
        }
        try {
            $user = User::byUsername($_POST['user_username']);
        } catch (Exception) {
            header('Location: /login');
            return;
        }

        if ($user->getPassword()->verify($_POST['user_password'])) {
            $_SESSION['user'] = $user->getId();
            header('Location: /');
        } else {
            header('Location: /login');
        }
    }

    public function register()
    {
        if (isset)
    }
}
