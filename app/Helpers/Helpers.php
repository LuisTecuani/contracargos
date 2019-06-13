<?php

if (!function_exists('fix_keys')) {
    /**
    Reindexa un array o un array de arrays
     * */

    function fix_keys($array) {
        foreach ($array as $k => $val) {
            if (is_array($val))
                $array[$k] = fix_keys($val); //recurse
        }
        return array_values($array);
    }

}


