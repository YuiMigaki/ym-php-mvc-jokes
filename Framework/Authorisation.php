<?php
/**
 * User Authorisation file
 *
 * This file is to check user authorisation
 *
 * Filename:        Authorisation.php
 * Location:        Framework/
 * Project:         ym-php-mvc-jokes
 * Date Created:    23/08/2024
 *
 * Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

namespace Framework;

class Authorisation
{
    /**
     * Check if current logged-in user owns a resource
     *
     * @param int $resourceId
     * @return bool
     */
    public static function isOwner(int $resourceId): bool
    {
        $sessionUser = Session::get('user');

        if ($sessionUser !== null && isset($sessionUser['id'])) {
            $sessionUserId = (int)$sessionUser['id'];
            return $sessionUserId === $resourceId;
        }

        return false;
    }


    /**
     * Check if current logged-in is the user being requested
     *
     * @param int $resourceId
     * @return bool
     */
    public static function isUser(int $resourceId): bool
    {
        $sessionUser = Session::get('user');

        if ($sessionUser !== null && isset($sessionUser['id'])) {
            $sessionUserId = (int)$sessionUser['id'];
            return $sessionUserId === $resourceId;
        }

        return false;
    }
}