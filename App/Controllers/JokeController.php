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
 *   Jokes Management Controller
 *
 *   This controller is to manage all the jokes
 *
 *   Filename:        JokeController.php
 *   Location:        /App/Controllers
 *   Project:         ym-php-mvc-jokes
 *   Date Created:    6/09/2024
 *
 *   Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */



namespace App\Controllers;

use Framework\Authorisation;
use Framework\Database;
use Framework\Session;
use Framework\Validation;

require __DIR__ . '/../../vendor/autoload.php';

use Parsedown;

class JokeController
{
    /* Properties */

    /**
     * @var Database
     */
    protected $db;

    /**
     * JokeController Constructor
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
     * Display a list of jokes.
     *
     * @return void
     */
    public function index()
    {
        $jokes = $this->db->query('SELECT * FROM jokes ORDER BY created_at DESC')->fetchAll();

        loadView('jokes/index', [
            'jokes' => $jokes
        ]);
    }

    /**
     * Show the joke creation form.
     *
     * @return void
     */
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

        $parsedown = new Parsedown();
        $jokeContent = $parsedown->text($joke->joke);


        loadView('jokes/show', [
            'joke' => $joke,
            'jokeContent' => $jokeContent
        ]);
    }

    /**
     * Store data in database
     *
     * @return void
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $allowedFields = ['title', 'joke', 'category_name', 'tags', 'author_name'];
            $newJokeData = array_intersect_key($_POST, array_flip($allowedFields));

            $newJokeData['user_id'] = Session::get('user')['id'];
            $newJokeData = array_map('sanitize', $newJokeData);

            $requiredFields = ['title', 'joke', 'category_name', 'tags', 'author_name'];
            $errors = [];

            // Validate required fields
            foreach ($requiredFields as $field) {
                if (empty($newJokeData[$field]) || !Validation::string($newJokeData[$field])) {
                    $errors[$field] = ucfirst($field) . ' is required';
                }
            }

            // Check for errors
            if (!empty($errors)) {
                // Return errors as JSON
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
//            if(!empty($errors)) {
//                loadView('jokes/create', [
//                    'joke' => $joke,
//                    'errors' => $errors
//                ]);
//                exit;
//            }


            // Prepare and execute the insert query
            $fields = implode(', ', array_keys($newJokeData));
            $values = ':' . implode(', :', array_keys($newJokeData));

            $query = "INSERT INTO jokes ({$fields}) VALUES ({$values})";
            $this->db->query($query, $newJokeData);

            Session::setFlashMessage('success_message', 'Joke created successfully');

            // Return success response
            echo json_encode(['success' => true]);
            exit;
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


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {

            $allowedFields = ['title','joke', 'category_name', 'tags', 'author_name'];

            $updateValues = [];

            $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

            $updateValues = array_map('sanitize', $updateValues);

            $requiredFields = ['title','joke', 'category_name', 'tags', 'author_name'];

            $errors = [];

            foreach($requiredFields as $field) {
                if(empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                    $errors[$field] = ucfirst($field) . ' is required';
                }
            }

//            if(!empty($errors)) {
//                loadView('jokes/edit', [
//                    'joke'=> $joke,
//                    'errors' => $errors
//                ]);
//                exit;

            // Check for errors
            if (!empty($errors)) {
                echo json_encode(['status' => 'error', 'errors' => $errors]);
                return;
            }
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


            echo json_encode(['status' => 'success']);

            exit;
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