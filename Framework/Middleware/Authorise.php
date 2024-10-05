<?php
/**
 * User Authenticate file
 *
 * This file is to check user authentication
 *
 * Filename:        Authorise.php
 * Location:        /Framework/Middleware
 * Project:         ym-php-mvc-jokes
 * Date Created:    23/08/2024
 *
 * Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

namespace Framework\Middleware;

use Framework\Session;

class Authorise
{
    /**
     * Handle the user's request
     *
     * @param string $role
     * @return bool
     */
    public function handle($role)
    {
        if ($role === 'guest' && $this->isAuthenticated()) {
            return redirect('/');
        }

        if ($role === 'auth' && !$this->isAuthenticated()) {
            return redirect('/auth/login');
        }
    }

    /**
     * Check if user is authenticated
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        return Session::has('user');
    }
}