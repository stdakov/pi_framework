<?php
namespace App\Core;

/**
 * Interface DTOInterface
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
interface DTOInterface
{
    /**
     * @param mixed $data
     */
    public function __construct($data = null);

    /**
     * @return integer
     */
    public function getId();

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id);
}
