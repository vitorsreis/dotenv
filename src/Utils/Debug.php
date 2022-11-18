<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv\Utils;

/**
 * Trait Debug
 *
 * @package DotEnv\Utils
 * @author  Vitor Reis <vitor@d5w.com.br>
 */
trait Debug
{
    /**
     * @var bool If "true" throw exceptions, else ignore or return "null"
     */
    private static $debug = false;

    /**
     * Method for get debug mode status
     * @return bool If "true" throw exceptions, else ignore or return "null"
     */
    public static function isDebug()
    {
        return static::$debug;
    }

    /**
     * Method for enable debug mode
     * @return void
     */
    public static function enableDebug()
    {
        self::$debug = true;
    }

    /**
     * Method for disable debug mode
     * @return void
     */
    public static function disableDebug()
    {
        self::$debug = false;
    }
}
