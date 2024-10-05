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
 *   Main Entry Point
 *
 *   This file serves as the main entry point for the ym-php-mvc-jokes application.
 *
 *   Filename:        index.php
 *   Location:        public/
 *   Project:         ym-php-mvc-jokes
 *   Date Created:    23/08/2024
 *
 *   Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

require __DIR__ . '/../vendor/autoload.php';

use Framework\Router;
use Framework\Session;

Session::start();

require '../helpers.php';

// Instantiate the router
$router = new Router();

// Get routes
$routes = require basePath('routes.php');

// Get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//echo password_hash("Password1",PASSWORD_DEFAULT);
//die;
// Route the request
$router->route($uri);
