<?php

/**
 * @author Ethann Schneider, Geoffroy Wildi, Jomana Kaempf, Nathan Chauveau
 * @version 08.01.2025
 * @description This is the controller of the login, logout and register 
 */

include_once MODEL_DIR . '/exercise.php';
include_once MODEL_DIR . '/user.php';
require_once MODEL_DIR . '/hashedPassword.php';
require_once MODEL_DIR . '/databases_connectors/databases_choose.php';

/**
 * FulfillmentController
 * This controller class handles actions related to the creation and editing of fulfillments.
 */
class AuthentificationController
{

    /**
     * Logout the user and send back to the home
     *
     * @return void
     */
    public function logout()
    {
        session_destroy();
        header('Location: /login');
    }

    /**
     * Login the user and send back to the home
     * If the username or the password is incorrect, an error will be display and the user will be send back to the login page
     *
     * @return void
     */
    public function login()
    {
        if (!isset($_POST['user_username'], $_POST['user_password'])) {
            badRequest();
        }
        try {
            $user = User::byUsername($_POST['user_username']);
        } catch (UserNotFoundException $error) {
            include VIEW_DIR . '/login.php';
            return;
        }

        if ($user->getPassword()->verify($_POST['user_password'])) {
            $_SESSION['user'] = $user->getId();
            header('Location: /');
        } else {
            $error = "Unto Teacher,
 
Hear me, good gentles! We didst order certain wares from thy website on the twenty-seventh day of November, in the year of our Lord two thousand and twenty-four. 'Twas proclaimed that said goods would arrive at mine hand on the second day of December, but lo! They have not yet appeared. This missive doth write itself on the twelfth day of December, a fortnight having passed since the appointed time.
Pray tell me, good Logitech, when shall we receive these long-awaited items? And we do implore thee, consider granting some compensation for the tardy delivery, lest our patience be sorely tried.
 
Yours faithfully,
Team GENJ";
            include VIEW_DIR . '/login.php';
            return;
        }
    }

    /**
     * Register the user and send back to the home
     * If the username already exist, an error will be display and the user will be send back to the register page
     *
     * @return void
     */
    public function register()
    {
        if (!isset($_POST['user_username'], $_POST['user_password'])) {
            badRequest();
        }

        $paswordhasched = HashedPassword::fromNonHashed($_POST['user_password']);

        try {
            $user = User::create($_POST['user_username'], $paswordhasched);
        } catch (UserAlreadyExistException $error) {
            include VIEW_DIR . '/register.php';
            return;
        }

        $_SESSION['user'] = $user->getId();
        header('Location: /');
    }
}
