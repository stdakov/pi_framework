<?php
namespace App\Http\Controller\Site\Op;

use App\Service\Post\Service;

class Test extends \App\Http\Controller\Site\Index
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $oPost = new Service();
        $oPost->blaa();
        echo "\n";

        $this->data['qko'] = 'basi kefa';

        $this->render('Op.Test.index');
    }

    public function get($a, $b = "")
    {
        echo $a;
        echo $b;
    }
}