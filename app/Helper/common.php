<?php

if (!function_exists('dd')) {

    function dd($arr)
    {
        echo "<pre>";
        print_r($arr);
        die();
    }

}
