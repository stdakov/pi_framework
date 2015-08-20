<?php

namespace App\Core;

/**
 * Class Config
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
class Config extends Instance
{
    protected $config = array();
    protected $loadedConf = [];

    public function __construct()
    {
    }

    /**
     * @param string $applicationConfig
     */
    public function init($applicationConfig)
    {
        $ini_array = parse_ini_file($applicationConfig, true);

        if (!empty($ini_array)) {
            $this->set($ini_array, 'app');
        }
    }

    /**
     * @param string $name
     * @return array
     * @throws \Exception
     */
    public function load($name = '')
    {
        $this->loadedConf = array();

        if ($name == '') {
            $this->loadedConf = $this->config;
        } elseif (array_key_exists($name, $this->config)) {
            $this->loadedConf = $this->config[$name];
        } else {
            throw new \Exception();
        }
        return $this;
    }

    /**
     * @param string $name
     *
     * @return array
     *
     * @throws \Exception
     */
    public function get($name = "")
    {
        if ($name == '') {
            $config = $this->config;
        } elseif (array_key_exists($name, $this->loadedConf)) {
            $config = $this->loadedConf[$name];
        } else {
            throw new \Exception();
        }
        return $config;
    }

    /**
     * @param array $config
     * @param string $name
     */
    public function set($config, $name)
    {
        $this->config[$name] = $config;
    }

    /**
     * @param string $configDir
     */
    public function loadConfig($configDir = "")
    {
        $configDir = ($configDir == "" ? __DIR__ . DIRECTORY_SEPARATOR . 'Config' : $configDir);
        $configFiles = glob(rtrim($configDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.php');
        foreach ($configFiles as $config) {
            ob_start();
            $option = require_once $config;
            $this->set($option, basename($config, '.php'));
            ob_end_clean();
        }

    }

    /**
     * @param string $helperDir
     */
    public function loadHelpers($helperDir)
    {
        $helperFiles = glob(rtrim($helperDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.php');
        foreach ($helperFiles as $helper) {
            require_once($helper);
        }

    }
}
