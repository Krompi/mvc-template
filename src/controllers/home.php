<?php
/**
 * Created by PhpStorm.
 * User: krompi
 * Date: 11.04.19
 * Time: 15:34
 */

class Home extends Controller
{
    public function index($name = '')
    {
        $user = $this->model('User');
        $user->name = $name;

        $this->view('home/index', ['name' => $user->name]);
    }
}