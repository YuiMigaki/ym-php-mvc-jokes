<?php
/**
 *   Assessment Title: AT2-POR-Pt2-MVC
 *   Cluster:          SaaS: Front-End Dev - ICT50220 (Advanced Programming)
 *   Qualification:    ICT50220 Diploma of Information Technology (Back End Web Development)
 *   Name:             Yui Migaki
 *   Student ID:       20098757
 *   Year/Semester:    2024/S2
 *
 *   MY SUMMARY OF PORTFOLIO ACTIVITY
 *   This portfolio activity involves implementing an online scratch course into a small MVC project that includes users, categories, and jokes.
 *
 *   UserAuthenticate Management Controller
 *
 *   This controller is to manage all the user authentication
 *
 *   Filename:        UserAuthController.php
 *   Location:        /App/Controllers
 *   Project:         ym-php-mvc-jokes
 *   Date Created:    6/09/2024
 *
 *   Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

namespace App\Controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;

class UserAuthController
{

    /* Properties */

    /**
     * @var Database
     */
    protected $db;

    /**
     * UserAuthController Constructor
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

    /**
     * Show the login page
     *
     * @return void
     */
    public function login()
    {
        loadView('usersAuth/login');
    }

    /**
     * Show the register page
     *
     * @return void
     */
    public function create()
    {
        loadView('usersAuth/create');
    }

    /**
     * Store user in database
     *
     * @return void
     */
    public function store()
    {
        $givenName = $_POST['given_name'] ?? null;
        $familyName = $_POST['family_name'] ?? null;
        $nickName = $_POST['nickname'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $passwordConfirmation = $_POST['password_confirmation'] ?? null;


        $errors = [];

        // Validation
        if (!Validation::string($givenName, 2, 50)) {
            $errors['given_name'] = 'Given Name must be between 2 and 50 characters';
        }
        if (!Validation::string($familyName, 2, 50)) {
            $errors['family_name'] = 'Family Name  must be between 2 and 50 characters';
        }
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        // Check if nickname is provided, if yes validate it, if not, set nickname to given name.
        if (!empty($nickName)) {
            if (!Validation::string($nickName, 2, 50)) {
                $errors['nickname'] = 'Nickname must be between 2 and 50 characters';
            }else {
                $nickName = $nickName;
            }
        }else {
            $nickName = $givenName;
        }

        if (!empty($errors)) {
            loadView('usersAuth/create', [
                'errors' => $errors,
                'user' => [
                    'given_name' => $givenName,
                    'family_name' => $familyName,
                    'nickname' => $nickName,
                    'email' => $email,
                ]
            ]);
            exit;
        }

        // Check if email exists
        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user) {
            $errors['email'] = 'That email already exists';
            loadView('usersAuth/create', [
                'errors' => $errors
            ]);
            exit;
        }

        // Create user account
        $params = [
            'given_name' => $givenName,
            'family_name' => $familyName,
            'nickname' => $nickName,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->db->query('INSERT INTO users (given_name, family_name, nickname, email, user_password) VALUES (:given_name, :family_name, :nickname, :email, :password)', $params);

        // Get new user ID
        $userId = $this->db->conn->lastInsertId();

        // Set user session
        Session::set('user', [
            'id' => $userId,
            'given_name' => $givenName,
            'family_name' => $familyName,
            'nickname' => $nickName,
            'email' => $email,
        ]);

        redirect('/');
    }

    /**
     * Logout a user and kill session
     *
     * @return void
     */
    public function logout(): void
    {
        Session::clearAll();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
    }

    /**
     * Authenticate a user with email and password
     *
     * @return void
     */
    public function authenticate()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        // Validation
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email';
        }

        if (!Validation::string($password, 6, 255)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        // Check for errors
        if (!empty($errors)) {
            loadView('usersAuth/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Check for email
        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if (!$user) {
            $errors['email'] = 'Incorrect credentials';
            loadView('usersAuth/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Check if password is correct

        if (!password_verify($password, $user->user_password)) {
            $errors['email'] = 'Incorrect credentials *';
            loadView('usersAuth/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Set user session
        Session::set('user', [
            'id' => $user->id,
            'given_name' => $user->given_name,
            'family_name' => $user->family_name,
            'nickname' => $user->nickname,
            'email' => $user->email,
        ]);

        redirect('/');
    }
}