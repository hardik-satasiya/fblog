<?php
namespace HS\Controllers\Backend;

use BackendMenu;
use Backend;
use HS\Classes\Controller as Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->layout = 'be-default';
    }
}
