<?php
namespace App\Core;

/**
 * Class Router
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
class Router extends Instance
{
    /** @var array $segments */
    private $segments = array();
    /** @var array $params */
    private $params = array();
    /** @var array $directories */
    private $directories = array();
    /** @var string $requestedClass */
    private $requestedClass = "";
    /** @var string $requestedMethod */
    private $requestedMethod = "";

    /**
     * Parses the URL and executes the requested method
     *
     * @return void
     */
    public function load()
    {
        // get everything after the host name
        $uriSegments = str_replace(SITE_URL, "", CURRENT_URL);

        // clean all get params
        $uriSegments = rtrim(trim(strtok($uriSegments, "?")), '/');

        $request = array();

        if ($uriSegments != "") {
            $request = $this->segments = explode("/", ltrim($uriSegments, "/"));
        }

        $this->loadDirectory($request);
    }

    /**
     * @param array $request
     */
    private function loadDirectory(array $request)
    {
        // check if we found some directories and generate the path
        $controllerPath = !empty($this->directories) ? implode(DIRECTORY_SEPARATOR, $this->directories) . DIRECTORY_SEPARATOR : "" . DIRECTORY_SEPARATOR;
        if (isset($request[0]) && $request[0] != '' && is_dir(CONTROLLER_DIR . $controllerPath . ucfirst(mb_strtolower($request[0])))) {
            // add found directory to found directories array
            $this->directories[] = ucfirst(mb_strtolower($request[0]));

            // remove found directory from request array and reset indexes (so we'll be able to call this function again recursively)
            unset($request[0]);
            $request = array_values($request);

            // call self recursively to find all nested directories
            $this->loadDirectory($request);

        } else {
            // if we are here then we found all the nested directories,
            // and now what is left is to load the controller and it's method
            $this->loadController($request);
        }
    }

    /**
     * @param array $request
     *
     * @throws RouterException
     */
    private function loadController(array $request)
    {
        $config = Config::getInstance();

        $controllerConf = $config->load('app')->get("controller");
        // check if we have more segments in the array
        if (count($request) > 0) {

            // get the first as controller class
            $this->requestedClass = ucfirst(mb_strtolower($request[0]));
            // reset the rest elements indexes
            unset($request[0]);
            $request = array_values($request);
        } else {
            // get the default one specified in the system config
            $this->requestedClass = ucfirst(mb_strtolower($controllerConf["default_controller"]));
        }

        if (!empty($this->directories)) {
            $this->requestedClass = CONTROLLER_NAMESPACE . implode('\\', $this->directories) . '\\' . $this->requestedClass;
        } else {
            $this->requestedClass = CONTROLLER_NAMESPACE . $this->requestedClass;
        }

        try {

            $reflector = new \ReflectionClass($this->requestedClass);

            if ($reflector->isAbstract() || $reflector->isInterface()) {
                $msg = 'Class "' . $this->requestedClass . '" is not callable.';
                die($msg);
            }

            $controller = $reflector->newInstance();

            if (count($request) > 0) {
                // if we have more segments get next as controller method called
                $this->requestedMethod = mb_strtolower($request[0]);

                // reset the rest elements indexes
                unset($request[0]);
                $request = array_values($request);

            } else {
                // get the default method specified in the system config
                $this->requestedMethod = mb_strtolower($controllerConf["default_method"]);
            }

            $this->params = $request;

            if ($reflector->getMethod($this->requestedMethod)->isPublic()) {
                call_user_func_array(array($controller, $this->requestedMethod), $this->params);
            }


        } catch (\ReflectionException $e) {
            if (DEBUG_MODE) {
                throw new RouterException($e->getMessage());
            } else {
                //show 404
            }

        }

    }

    /**
     * @return array
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }


    /**
     * @return string
     */
    public function getRequestedClass()
    {
        return $this->requestedClass;
    }

    /**
     * @return string
     */
    public function getRequestedMethod()
    {
        return $this->requestedMethod;
    }

}
