<?php
namespace App\Http\Controller\Site;
use App\Service\Post\Service;

class Home extends Index
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $post = new Service();

        $this->render('Home.index');
    }

    public function test()
    {
        $this->render('Home.test');
    }
}