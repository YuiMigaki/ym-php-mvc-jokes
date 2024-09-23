<?php
/**
 * FILE TITLE GOES HERE
 *
 * DESCRIPTION OF THE PURPOSE AND USE OF THE CODE
 * MAY BE MORE THAN ONE LINE LONG
 * KEEP LINE LENGTH TO NO MORE THAN 96 CHARACTERS
 *
 * Filename:        StaticPageController.php
 * Location:
 * Project:         XXX-PHP-MVC-Jokes
 * Date Created:    DD/MM/YYYY
 *
 * Author:          YOUR NAME <STUDENT_ID@tafe.wa.edu.au>
 *
 */

namespace App\Controllers;


use Framework\Database;

class StaticPageController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /*
     * Show the home page
     *
     * @return void
     */
    public function index()
    {
        // TODO: Crete the method code for the home page
        $listings = $this->db->query('SELECT * FROM users ORDER BY created_at DESC LIMIT 6')->fetchAll();

        loadView('home', [
            'listings'=> $listings
        ]);




    }

    /*
     * Show the about static page
     *
     * @return void
     */
    public function about()
    {
        // TODO: Crete the method code for the about page
    }
}