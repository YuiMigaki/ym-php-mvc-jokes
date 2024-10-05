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
 *   Utilities File
 *
 *   This file contains utility functions for debugging and data output.
 *
 *   Filename:        Utilities.php
 *   Location:        includes/
 *   Project:         ym-php-mvc-jokes
 *   Date Created:    23/08/2024
 *
 *   Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

class Utilities
{
    public static function dump(): void
    {
        echo "<pre class='bg-gray-100 color-black m-2 p-2 rounded shadow flex-grow text-sm'>";
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        echo "</pre>";
    }

    public static function dd(): void
    {
        echo "<pre class='bg-gray-100 color-black m-2 p-2 rounded shadow flex-grow text-sm'>";
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        echo "</pre>";
        die();
    }
}


