<?php

namespace App\Core;

/**
 * Class DTO
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
abstract class DTO implements DTOInterface
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @param array|object|null $data
     *
     * @return self
     */
    public function __construct($data = null)
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            foreach ($data as $index => $value) {
                $method = 'set' . ucwords($index);
                if (method_exists($this, $method)) {
                    call_user_func_array(array($this, $method), array($value));
                }
            }
        }

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setid($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }
}