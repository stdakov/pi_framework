<?php
namespace App\Core;

/**
 * Class Instance
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
abstract class Instance
{
    private static $instances = array();

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot un serialize singleton");
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        $calledClass = get_called_class();
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new static();
        }
        return self::$instances[$calledClass];
    }
}