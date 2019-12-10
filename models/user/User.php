<?php

namespace app\models\user;


/**
 * Class User
 * @package app\models\user
 */
class User extends \Da\User\Model\User
{
    public function getCorrectName()
    {
        return $this->profile->name ?: $this->username;
    }
}
