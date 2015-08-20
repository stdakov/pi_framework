<?php
namespace App\Core;

/**
 * Class Controller
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
abstract class Controller
{
    /** @var bool  */
    protected $isAjax = false;
    /** @var array  */
    protected $data = array(); // the data passed to the controllers
    /** @var Session */
    public $session = null;
    /** @var Request */
    public $request = null;
    /** @var Router */
    public $router = null;
    /** @var View */
    public $view = null;

    public function __construct()
    {
        $this->init();

        // determine if this is an AJAX call or not
        $this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    private function init()
    {
        $this->session = Session::getInstance();
        $this->request = Request::getInstance();
        $this->router = Router::getInstance();
        $this->view = View::getInstance();
    }

    /**
     * @param $view
     */
    public function render($view)
    {
        $this->view->render($view, $this->data);
    }
}