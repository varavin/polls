<?php
/**
 * Created by PhpStorm.
 * User: varavin
 * Date: 05.01.2019
 * Time: 15:42
 */

namespace Polls\Controllers;

class SiteController extends Controller
{
    public function index()
    {
        return $this->app->renderView('index', ['a' => 'aaaaaaaaa']);
    }
}