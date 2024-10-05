<?php
/**
 * User Management Controller
 *
 * This controller is to manage all the user
 *
 * Filename:        UserController.php
 * Location:        /App/Controllers
 * Project:         ym-php-mvc-jokes
 * Date Created:    6/09/2024
 *
 * Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

namespace App\Controllers;

use Framework\Authorisation;
use Framework\Database;
use Framework\Session;
use Framework\Validation;

class UserController
{
    /* Properties */

    /**
     * @var Database
     */
    protected $db;

    /**
     * UserController Constructor
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

    // TODO: Create the index method
    /**
     * Display a list of users.
     *
     * @return void
     */
    public function index()
    {
        $sql = "SELECT * FROM users ORDER BY nickname, email, created_at";

        $users = $this->db->query($sql)->fetchAll();


        loadView('users/index', [
            'users' => $users
        ]);
    }

    // TODO: Create the show method

    /**
     * Show a single user
     *
     * @param array $params
     * @return void
     */
    public function show($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

//        $sql = 'SELECT * FROM users WHERE id = :id ';
        $sql = "SELECT u1.id as id, u1.given_name as given_name, u1.family_name as family_name, u1.email as email,
                       u1.user_id as user_id, u1.created_at as created_at, u1.updated_at as updated_at,
                       CONCAT(u2.given_name, ' ', u2.family_name) AS added_by
                FROM users u1
                JOIN users u2 ON u1.user_id = u2.id
                WHERE u1.id = :id;";
        $user = $this->db->query($sql, $params)->fetch();

        // Check if user exists
        if (!$user) {
            ErrorController::notFound('User not found');
            return;
        }

        loadView('users/show', [
            'user' => $user
        ]);
    }

    // TODO: Create a search method for users

    /**
     * Search users by keywords/location
     *
     * @return void
     */
    public function search()
    {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';

        $query = "SELECT * FROM users 
                  WHERE given_name LIKE :keywords 
                     OR family_name LIKE :keywords 
                     OR nickname LIKE :keywords 
                     OR email LIKE :keywords 
                  ORDER BY given_name, family_name ,nickname ";

        $params = [
            'keywords' => "%{$keywords}%",
        ];

        $users = $this->db->query($query, $params)->fetchAll();

        loadView('/users/index', [
            'users' => $users,
            'keywords' => $keywords,
        ]);
    }

    // TODO: Create the create method

    /**
     * Show the create user form
     *
     * @return void
     */
    public function create()
    {
        loadView('users/create');

//        $currentUserId = $_SESSION['id'] ?? null;
//
//
//        if ($currentUserId !== 10) {
//            $errors['id'] = 'You are not authorised to add new registrations.';
//            loadView('users/create', [
//                'errors' => $errors
//            ]);
//            exit;
//        }
    }

    // TODO: Create the store

    /**
     * Store data in database
     *
     * @return void
     */
    public function store($params)

    {


        $allowedFields = ['given_name', 'family_name', 'nickname', 'email', 'user_password', 'confirm_password',];

        $newUserData = array_intersect_key($_POST, array_flip($allowedFields));
        $newUserData['user_id'] = Session::get('user')['id'];
        $newUserData = array_map('sanitize', $newUserData);

        $requiredFields = ['given_name', 'family_name', 'email', 'user_password', 'confirm_password'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newUserData[$field]) || !Validation::string($newUserData[$field])) {
                $errors[$field] = ucfirst(str_replace("_", " ", $field)) . ' <em>is required</em>';
            }
        }

        // Check nickname if provided, otherwise use given name
        $nickname = $newUserData['nickname'] ?? '';
        if ($nickname) {
            if (!Validation::string($newUserData[$field])) {
                $errors[$field] = ucfirst(str_replace("_", " ", $field)) . ' <em>is required</em>';
            }
        } else {
            $newUserData['nickname'] = $newUserData['given_name'];
        }

        $_POST['user_password'] = trim($_POST['user_password'] ?? null);
        $_POST['confirm_password'] = trim($_POST['confirm_password'] ?? null);


        if (!Validation::string($_POST['user_password'], 6)) {
            $errors['user_password'] = 'Password must be 6 or more characters';
        }
        if (!Validation::string($_POST['confirm_password'], 6)) {
            $errors['confirm_password'] = 'Password Confirmation must be 6 or more characters';
        }

        if (!Validation::match($_POST['confirm_password'], $_POST['user_password'])) {
            $errors['password_match'] = 'Passwords do not match';
        }

        $params = [
            'email' => $newUserData['email']
        ];

        // Check if email exists
        $emailExist = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($emailExist) {
            $errors['email'] = 'That email already exists';
        }

        if (!empty($errors)) {
            // Reload the form with errors
            loadView('users/create', [
                'errors' => $errors,
                'user' => $newUserData
            ]);
            exit();
        }



        if (!empty($_POST['user_password'])) {
            $hashOptions = [
                'cost' => 12,
            ];
            $passwordHash = password_hash($_POST['user_password'], PASSWORD_BCRYPT, $hashOptions);
            $newUserData['user_password'] = $passwordHash;
        }

        // Remove confirm_password from the data to be inserted
        unset($newUserData['confirm_password']);

        // Save the submitted data
        $fields = [];

        foreach ($newUserData as $field => $value) {
            $fields[] = $field;
        }

        $fields = implode(', ', $fields);
        $values = [];


        foreach ($newUserData as $field => $value) {
            // Convert empty strings to null
            if ($value === '') {
                $newUserData[$field] = null;
            }
            $values[] = ':' . $field;
        }


        $values = implode(', ', $values);

        $insertQuery = "INSERT INTO users ({$fields}) VALUES ({$values})";

        $this->db->query($insertQuery, $newUserData);

        Session::setFlashMessage('success_message', 'User created successfully');

        redirect('/users');
    }

    /**
     * Show the user edit form
     *
     * @param array $params
     * @return null
     * @throws \Exception
     */
    public function edit($params): null
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $user = $this->db->query('SELECT * FROM users WHERE id = :id', $params)->fetch();

        // Check if user exists
        if (!$user) {
            ErrorController::notFound('User not found');
            exit();
        }

        // Authorisation
        if (!Authorisation::isOwner($user->user_id) && !Authorisation::isUser($user->id)) {
            Session::setFlashMessage('error_message',
                'You are not authorized to update this user');
            return redirect('/users/' . $user->id);
        }

        loadView('users/edit', [
            'user' => $user
        ]);
    }

    /**
     * Update a user
     *
     * @param array $params
     * @return null
     */
    public function update($params): null
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $user = $this->db->query('SELECT * FROM users WHERE id = :id', $params)->fetch();

        // Check if user exists
        if (!$user) {
            ErrorController::notFound('User not found');
            exit();
        }

        // Authorisation
        if (!Authorisation::isOwner($user->user_id) && !Authorisation::isUser($user->id)) {
            Session::setFlashMessage('error_message',
                'You are not authorised to update this user');
            return redirect('/users/' . $user->id);
        }

        $allowedFields = ['given_name', 'family_name', 'nickname', 'email', 'user_password', 'confirm_password',];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields)) ?? [];

        $updateValues = array_map('sanitize', $updateValues);


        $requiredFields = ['given_name', 'family_name','email', 'user_password', 'confirm_password'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        // Check nickname if provided, otherwise set it to given name
        $nickname = $updateValues['nickname'] ?? '';
        if ($nickname) {
            if (!Validation::string($nickname)) {
                $errors['nickname'] = 'Nickname must be a valid string';
            }
        } else {
            $updateValues['nickname'] = $updateValues['given_name'];
        }

        $_POST['user_password'] = trim($_POST['user_password'] ?? null);
        $_POST['confirm_password'] = trim($_POST['confirm_password'] ?? null);


        if (!Validation::string($_POST['user_password'], 6)) {
            $errors['user_password'] = 'Password must be 6 or more characters';
        }
        if (!Validation::string($_POST['confirm_password'], 6)) {
            $errors['confirm_password'] = 'Password Confirmation must be 6 or more characters';
        }

        if (!Validation::match($_POST['confirm_password'], $_POST['user_password'])) {
            $errors['password_match'] = 'Passwords do not match';
        }

//        if (!Validation::string($_POST['user_password'], 1) && !Validation::string($_POST['confirm_password'], 1)) {
//            unset($_POST['user_password'], $_POST['confirm_password']);
//            unset($errors['user_password'], $errors['confirm_password'], $errors['password_match']);
//        }


        $currentEmail = $user->email;

        $params = [
            'email' => $updateValues['email'],
            'current_email' => $currentEmail
        ];

        // Check if email exists
        $emailUpdate = $this->db->query('SELECT * FROM users WHERE email = :email AND email != :current_email', $params)->fetch();

        if ($emailUpdate) {
            $errors['email'] = 'That email already exists';
        }

        if (!empty($errors)) {
            loadView('users/edit', [
                'user' => $user,
                'errors' => $errors
            ]);
            exit();
        }

        // Remove confirm_password from the data to be inserted
        unset($updateValues['confirm_password']);

        // Submit to database
        $updateFields = [];

        if (!empty($_POST['user_password'])) {
            $hashOptions = [
                'cost' => 12,
            ];
            $passwordHash = password_hash($_POST['user_password'], PASSWORD_BCRYPT, $hashOptions);
            $updateValues['user_password'] = $passwordHash;
        }

        $updateValues['updated_at'] = date('Y-m-d H:i:s');

        foreach (array_keys($updateValues) as $field) {
            $updateFields[] = "{$field} = :{$field}";
        }

        $updateFields = implode(', ', $updateFields);

        $updateQuery = "UPDATE users SET $updateFields WHERE id = :id";

        $updateValues['id'] = $id;
        $this->db->query($updateQuery, $updateValues);

        // Set flash message
        Session::setFlashMessage('success_message', 'User updated');

        redirect('/users/' . $id);
    }

    // TODO: Create the destroy method

    /**
     * Delete a user
     *
     * @param array $params
     * @return void|null
     * @throws \Exception
     */
    public function destroy($params)
    {
        $id = $params['id'];

        $params = [
            'id' => $id
        ];

        $user = $this->db->query('SELECT * FROM users WHERE id = :id', $params)->fetch();

        // Check if user exists
        if (!$user) {
            ErrorController::notFound('User not found');
            exit();
        }

        // Authorisation
        if (!Authorisation::isOwner($user->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authoirzed to delete this user');
            return redirect('/users/' . $user->id);
        }

        $this->db->query('DELETE FROM users WHERE id = :id', $params);

        // Set flash message
        Session::setFlashMessage('success_message', 'User deleted successfully');

        redirect('/users');
    }


}