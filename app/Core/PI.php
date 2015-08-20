<?php

namespace App\Core;

/**
 * Class PI
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
class PI
{
    /** @var \App\Core\Config $config */
    protected $config;
    /** @var \App\Core\Router $router */
    protected $router;

    public function __construct()
    {
        $this->initConfig();
        $this->initRouter();
    }

    public function initConfig()
    {
        $this->config = \App\Core\Config::getInstance();
        $this->config->init(CONFIG_INI);
        $this->config->loadConfig(CONFIG_DIR);
        $this->config->loadHelpers(HELPER_DIR);
    }

    public function initRouter()
    {
        $this->router = \App\Core\Router::getInstance();
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }


    public function run()
    {
        $this->router->load();
    }


}