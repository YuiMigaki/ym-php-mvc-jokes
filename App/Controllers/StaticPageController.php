<?php
/**
 * StaticPage Management Controller
 *
 * This controller is to manage when static page including home and about
 *
 * Filename:        StaticPageController.php
 * Location:        /App/Controllers
 * Project:         ym-php-mvc-jokes
 * Date Created:    6/09/2024
 *
 * Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Parsedown;
use Framework\Database;

class StaticPageController
{
    /* Properties */

    /**
     * @var Database
     */
    protected $db;


    /**
     * StaticPageController Constructor
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
     * Show the home page
     *
     * @return void
     * **/

    public function index()
    {
        // TODO: Crete the method code for the home page
        $users = $this->db->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
        $userCount = count($users);
        $jokes = $this->db->query('SELECT * FROM jokes ORDER BY created_at DESC')->fetchALL();
        $jokeCount = count($jokes);
        $categories = $this->db->query('SELECT * FROM categories ORDER BY created_at DESC')->fetchAll();
        $categoryCount = count($categories);
        $random = $this->db->query('SELECT * FROM jokes ORDER BY RAND()LIMIT 1')->fetch();


        $parsedown = new Parsedown();
        $jokeContent = $parsedown->text($random->joke);


        loadView('home', [
            'users' => $users,
            'jokes' => $jokes,
            'categories' => $categories,
            'categoryCount' => $categoryCount,
            'jokeCount' => $jokeCount,
            'userCount' => $userCount,
            'random' => $random,
            'jokeContent' => $jokeContent

        ]);
    }


    /**
     * Show the about static page
     *
     * @return void
     **/
    public function about()
    {
        // TODO: Crete the method code for the about page

        loadView('about');
    }


    /**
     * Search for jokes, users, or categories based on keywords.
     *
     * @return void
     */
    public function search()
    {

        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';

        if (empty($keywords)) {
            redirect('/');
        }

            $jokeQuery = "SELECT * FROM jokes WHERE (title LIKE :keywords OR joke LIKE :keywords OR category_name LIKE :keywords OR tags LIKE :keywords OR author_name LIKE :keywords)";
            $userQuery = "SELECT * FROM users WHERE (given_name LIKE :keywords OR family_name LIKE :keywords OR nickname LIKE :keywords OR email LIKE :keywords) ORDER BY given_name, family_name, nickname";
            $categoryQuery = "SELECT * FROM categories WHERE (name LIKE :keywords)";

            $params = [
                'keywords' => "%{$keywords}%"
            ];

            $jokes = $this->db->query($jokeQuery, $params)->fetchAll();
            $users = $this->db->query($userQuery, $params)->fetchAll();
            $categories = $this->db->query($categoryQuery, $params)->fetchAll();


            if (!empty($jokes)) {
                loadView('/jokes/index', [
                    'jokes' => $jokes,
                    'keywords' => $keywords
                ]);
            } elseif (!empty($users)) {
                loadView('/users/index', [
                    'users' => $users,
                    'keywords' => $keywords
                ]);
            } elseif (!empty($categories)) {
                loadView('/categories/index', [
                    'categories' => $categories,
                    'keywords' => $keywords
                ]);
            }else {
                loadView('home', [
                    'keywords' => $keywords
                ]);
            }
        }
    }

