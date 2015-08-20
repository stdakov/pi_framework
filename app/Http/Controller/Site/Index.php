<?php
namespace App\Http\Controller\Site;

abstract class Index extends \App\Core\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function render($view)
    {
        parent::render('include.header');
        parent::render($view);
        parent::render('include.footer');
    }
}