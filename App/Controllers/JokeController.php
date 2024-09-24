<?php
/**
 * Joke Management Controller
 *
 * Filename:        JokeController.php
 * Location:        /App/Controllers
 * Project:         XXX-mvc-jokes
 * Date Created:    6/09/2024
 *
 * :          YOUR NAME <STUDENT_ID@tafe.wa.edu.au>
 *
 */

namespace App\Controllers;

use Framework\Authorisation;
use Framework\Database;
use Framework\Session;
use Framework\Validation;


class JokeController
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

    public function index()
    {
        $jokes = $this->db->query('SELECT * FROM jokes ORDER BY created_at DESC')->fetchAll();

        loadView('jokes/index', [
            'jokes' => $jokes
        ]);
    }

    public function create()
    {
        loadView('jokes/create');
    }

    /**
     * Show a single joke
     * @param array $params
     * @return void
     */
    public function show($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $joke = $this->db->query('SELECT * FROM jokes WHERE id = :id', $params)->fetch();

        //Check if the joke exits
        if (!$joke) {
            ErrorController::notFound('Joke not found');
            return;
        }

        loadView('jokes/show', [
            'joke' => $joke
        ]);
    }

    /**
     * Store data in database
     *
     * @return void
     */
    public function store()
    {
        $allowedFields = ['joke', 'category_name', 'tags', 'author_name'];

        $newJokeData = array_intersect_key($_POST, array_flip($allowedFields));

        $newJokeData['user_id'] = Session::get('user')['id'];

        $newJokeData = array_map('sanitize', $newJokeData);

        $requiredFields = ['joke', 'category_name', 'tags', 'author_name'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newJokeData[$field]) || !Validation::string
                ($newJokeData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            };
        }

        if (!empty($errors)) {
            //Reload view with errors
            loadView('jokes/create', [
                'errors' => $errors,
                'joke' => $newJokeData

            ]);
        } else {
            // Submit data

            $fields = [];

            foreach ($newJokeData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(', ', $fields);

            $values = [];

            foreach ($newJokeData as $field => $value) {
                //Convert empty strings to null
                if ($value === '') {
                    $newJokeData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO jokes ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newJokeData);

            Session::setFlashMessage('success_message', 'Joke created successfully');

            redirect('/jokes');
        }
    }

    /**
     *  Delete a joke
     *
     * @param array $params
     * @return void
     */
    public function destroy($params)
    {
        $id = $params['id'];

        $params = [
            'id' => $id
        ];

        $joke = $this->db->query('SELECT * FROM jokes WHERE id = :id', $params)->fetch();

        //Check if joke exists
        if (!$joke) {
            ErrorController::notFound('Joke not found');
            return;
        }
        // Authorisation
        if (!Authorisation::isOwner($joke->user_id))
        {
            Session::setFlashMessage('error_message', 'You are not authorised to delete this joke');
            return redirect('/jokes/' . $joke->id);
        }

        $this->db->query('DELETE FROM jokes WHERE id = :id', $params);

        // Set flash message
        Session::setFlashMessage('success_message', 'Joke deleted successfully');
        redirect('/jokes');
    }

    /**
     * Show the joke edit form
     * @param array $params
     * @return void
     */
    public function edit($params)
    {

        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $joke = $this->db->query('SELECT * FROM jokes WHERE id = :id', $params)->fetch();

        //Check if the joke exits
        if (!$joke) {
            ErrorController::notFound('Joke not found');
            return;
        }

        if (!Authorisation::isOwner($joke->user_id))
        {
            Session::setFlashMessage('error_message', 'You are not authorised to update this joke');
            return redirect('/jokes/' . $joke->id);
        }

        loadView('jokes/edit', [
            'joke' => $joke
        ]);
    }

    /**
     * Update a joke
     *
     * @param array $params
     * @return void
     */
    public function update($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $joke = $this->db->query('SELECT * FROM jokes WHERE id = :id', $params)->fetch();

        //Check if the joke exits
        if (!$joke) {
            ErrorController::notFound('Joke not found');
            return;
        }

        if (!Authorisation::isOwner($joke->user_id))
        {
            Session::setFlashMessage('error_message', 'You are not authorised to update this joke');
            return redirect('/jokes/' . $joke->id);
        }

        $allowedFields = ['joke', 'category_name', 'tags', 'author_name'];

        $updateValues = [];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['joke', 'category_name', 'tags', 'author_name'];

        $errors = [];

        foreach($requiredFields as $field) {
            if(empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if(!empty($errors)) {
            loadView('jokes/edit', [
                'joke'=> $joke,
                'errors' => $errors
            ]);
            exit;
        } else {
            // Submit to database
            $updateFields = [];

            $updateValues['updated_at'] = date('Y-m-d H:i:s');

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(', ', $updateFields);

            $updateQuery = "UPDATE jokes SET $updateFields WHERE id = :id";

            $updateValues['id'] = $id;
            $this->db->query($updateQuery, $updateValues);


            Session::setFlashMessage('success_message', 'Joke Updated');

            redirect('/jokes/' . $id);
        }
    }

    /**
     * Search jokes by keywords
     *
     * @return void
     *
     */
    public function search()
    {

        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';

        $query = "SELECT * FROM jokes WHERE (joke LIKE :keywords OR category_name LIKE :keywords OR tags LIKE :keywords OR author_name LIKE :keywords)";

        $params = [
            'keywords' => "%{$keywords}%"
        ];

        $jokes = $this->db->query($query, $params)->fetchAll();

        loadView('/jokes/index', [
            'jokes' => $jokes,
            'keywords' => $keywords
        ]);

    }

}