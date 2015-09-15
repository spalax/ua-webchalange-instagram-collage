<?php
namespace Frontend\Stdlib\Debug;

/**
 * @package    Debug
 * @copyright  Copyright (c) 2010-2013 Milcrew Inc. (http://www.milcrew.com)
 * @license    http://milcrew.com/public/LICENSE.txt    New BSD License
 */
class Utility {
    /**
     * Function for debug
     *
     * Display all recived parameters with
     * good formating in Browser and exit after
     *
     * @param mixed
     */
    public static function dump()
    {
        $p = func_get_args();
        foreach ($p as $arg) {
            self::dumpAlive($arg);
        }
        exit(0);
    }

    /**
     * @see dump()
     * no exit at the end
     */
    public static function dumpAlive()
    {
        echo "<pre>";

        $i = 1;
        $args = func_get_args();
        array_walk_recursive($args, function(&$item){
            if ($item === "") {
                $item = '""';
            } elseif ($item === true) {
                $item = 'true (boolean)';
            } elseif  ($item === false) {
                $item = 'false (boolean)';
            } elseif (is_null($item)) {
                $item = 'NULL';
            }
        });

        foreach ($args as $arg) {
            echo "VAR ".$i++."\n";
            print_r($arg);
            echo "\n\n";
        }
        echo "</pre>";
    }
}
