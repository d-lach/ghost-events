<?php

namespace App;

/**
 * @package App
 */
class UsersRepository
{
    function __construct() {
    }

    /**
     * @param string $email
     * @return User|null
     */
    function findByEmail(string $email)
    {
        return User::where('email',$email) -> first();
    }
}
