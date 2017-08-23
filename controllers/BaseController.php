<?php
namespace HS\Controllers;

use BackendMenu;
use Backend;
use HS\Classes\Controller as Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->layout = 'front-default';
    }
}
