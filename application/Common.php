<?php

###########公共函数部分###########
if (!function_exists('p')) {
    function p($arr)
    {
        echo '<pre>', print_r($arr), '</pre>';
    }
}