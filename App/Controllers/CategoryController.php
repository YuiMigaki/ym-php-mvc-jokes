<?php
/**
 * Category Management Controller
 *
 * This controller is to manage categories
 *
 * Filename:        CategoryController.php
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

class CategoryController
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
        $categories = $this->db->query('SELECT * FROM categories ORDER BY created_at DESC')->fetchAll();

        loadView('categories/index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        loadView('categories/create');
    }

    /**
     * Show a single category
     * @param array $params
     * @return void
     **/

    public function show($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $categories = $this->db->query('SELECT * FROM categories WHERE id = :id', $params)->fetch();

        //Check if the category exits
        if (!$categories) {
            ErrorController::notFound('Category not found');
            return;
        }

        loadView('categories/show', [
            'category' => $categories
        ]);
    }

    /**
     * Store data in database
     *
     * @return void
     */

    public function store()
    {
        $allowedFields = ['name'];

        $newCategoryData = array_intersect_key($_POST, array_flip($allowedFields));

        $newCategoryData['user_id'] = Session::get('user')['id'];

        $newCategoryData = array_map('sanitize', $newCategoryData);

        $requiredFields = ['name'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newCategoryData[$field]) || !Validation::string
                ($newCategoryData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            };
        }

        if (!empty($errors)) {
            //Reload view with errors
            loadView('categories/create', [
                'errors' => $errors,
                'category' => $newCategoryData

            ]);
        } else {
            // Submit data

            $fields = [];

            foreach ($newCategoryData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(', ', $fields);

            $values = [];

            foreach ($newCategoryData as $field => $value) {
                //Convert empty strings to null
                if ($value === '') {
                    $newCategoryData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO categories ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newCategoryData);

            Session::setFlashMessage('success_message', 'Category created successfully');

            redirect('/categories');
        }
    }

    /**
     *  Delete a category
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

        $category = $this->db->query('SELECT * FROM categories WHERE id = :id', $params)->fetch();

        //Check if category exists
        if (!$category) {
            ErrorController::notFound('Category not found');
            return;
        }
        // Authorisation
        if (!Authorisation::isOwner($category->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorised to delete this category');
            return redirect('/categories/' . $category->id);
        }

        $this->db->query('DELETE FROM categories WHERE id = :id', $params);

        // Set flash message
        Session::setFlashMessage('success_message', 'Category deleted successfully');
        redirect('/categories');
    }

    /**
     * Show the category edit form
     * @param array $params
     * @return void
     */
    public function edit($params)
    {

        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $category = $this->db->query('SELECT * FROM categories WHERE id = :id', $params)->fetch();

        //Check if the category exits
        if (!$category) {
            ErrorController::notFound('Category not found');
            return;
        }

        if (!Authorisation::isOwner($category->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorised to update this category');
            return redirect('/categories/' . $category->id);
        }

        loadView('categories/edit', [
            'category' => $category
        ]);
    }

    /**
     * Update a category
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

        $category = $this->db->query('SELECT * FROM categories WHERE id = :id', $params)->fetch();

        //Check if the category exits
        if (!$category) {
            ErrorController::notFound('Category not found');
            return;
        }

        if (!Authorisation::isOwner($category->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorised to update this category');
            return redirect('/categories/' . $category->id);
        }

        $allowedFields = ['name'];

        $updateValues = [];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['name'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('categories/edit', [
                'category' => $category,
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

            $updateQuery = "UPDATE categories SET $updateFields WHERE id = :id";

            $updateValues['id'] = $id;
            $this->db->query($updateQuery, $updateValues);


            Session::setFlashMessage('success_message', 'Category Updated');

            redirect('/categories/' . $id);
        }
    }

    /**
     * Search categories by keywords
     *
     * @return void
     *
     */
    public function search()
    {

        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';

        $query = "SELECT * FROM categories WHERE (name LIKE :keywords)";

        $params = [
            'keywords' => "%{$keywords}%"
        ];

        $categories = $this->db->query($query, $params)->fetchAll();

        loadView('/categories/index', [
            'categories' => $categories,
            'keywords' => $keywords
        ]);

    }
}

