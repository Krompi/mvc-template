<?php
/**
 * Created by PhpStorm.
 * User: krompi
 * Date: 11.04.19
 * Time: 15:31
 */

class Controller
{
    public function model($model)
    {
        require_once '../src/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = [])
    {
        require_once '../src/views/' . $view . '.php';
    }
}