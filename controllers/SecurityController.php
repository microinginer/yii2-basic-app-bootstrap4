<?php


namespace app\controllers;


/**
 * Class SecurityController
 * @package app\controllers
 */
class SecurityController extends \Da\User\Controller\SecurityController
{
    public $layout = '@app/modules/adminable/views/layouts/sign';
}
