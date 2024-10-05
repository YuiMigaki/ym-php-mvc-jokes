<?php
/**
 * CreatePassword Management Controller
 *
 * This controller is to create password
 *
 * Filename:        CreatePasswordController.php
 * Location:        /App/Controllers
 * Project:         ym-php-mvc-jokes
 * Date Created:    6/09/2024
 *
 * Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

namespace App\Controllers;


use Framework\Database;
use Framework\Validation;

class CreatePasswordController
{
    protected $db;

    /**
     * CreatePasswordController Constructor
     *
     * Instantiate the database connection for use in this class
     * storing the connection in the protected <code>$db</code>
     * property.
     *
     * @throws \Exception
     */

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /*
     * Show the password creation form or process the submitted password.
     *
     * @return void
     */
    public function index()
    {

        if (!isset($_POST['password']))        {
            loadView('create-password', [
            ]);

        } else {
            $password = trim($_POST['password']);

            $errors = [];

            // Validation
            if (!Validation::string($password, 6, 255)) {
                $errors['password'] = 'Password must be at least 6 characters';
            }

            // Check for errors
            if (!empty($errors)) {
                loadView('create-password', [
                    'errors' => $errors,
                    'password' => $password,

                ]);
                exit;
            }

            $hashOptions = [
                'cost' => 12,
            ];
            $passwordHash = password_hash($password, PASSWORD_BCRYPT, $hashOptions);

            loadView('create-password', [
                'password' => $password,
                'passwordHash' => $passwordHash,
            ]);
        }
    }
}