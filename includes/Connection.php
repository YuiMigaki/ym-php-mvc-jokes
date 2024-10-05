<?php
/**
 * Connection File
 *
 * This file is to check database connection
 *
 * Filename:        Connection.php
 * Location:        includes/
 * Project:         ym-php-mvc-jokes
 * Date Created:    23/08/2024
 *
 * Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

require_once __DIR__.'/../config.php';

class Connection
{
    public static function make($host, $db, $username, $password)
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

        try {
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

            return new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }
}

return Connection::make($dbHost, $dbName, $dbUser, $dbPass);

