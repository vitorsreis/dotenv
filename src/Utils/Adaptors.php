<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv\Utils;

/**
 * Trait Adaptors
 *
 * @package DotEnv\Utils
 * @author  Vitor Reis <vitor@d5w.com.br>
 */
trait Adaptors
{
    private static $adaptors = [
        'ApacheGetEnv'      => false,
        'Constant'          => false,
        'EnvSuperGlobal'    => true,
        'GetEnv'            => true,
        'ServerSuperGlobal' => false,
    ];

    /**
     * Method for enable ApacheGetEnv Adaptor
     * @return void
     */
    public static function enableApacheGetEnvAdaptor()
    {
        static::$adaptors['ApacheGetEnv'] = true;
    }

    /**
     * Method for disable ApacheGetEnv Adaptor
     * @return void
     */
    public static function disableApacheGetEnvAdaptor()
    {
        static::$adaptors['ApacheGetEnv'] = false;
    }

    /**
     * Method for enable Constant Adaptor <b>Not recommended</b>
     * @return void
     */
    public static function enableConstantAdaptor()
    {
        static::$adaptors['Constant'] = true;
    }

    /**
     * Method for disable Constant Adaptor
     * @return void
     */
    public static function disableConstantAdaptor()
    {
        static::$adaptors['Constant'] = false;
    }

    /**
     * Method for enable EnvSuperGlobal Adaptor
     * @return void
     */
    public static function enableEnvSuperGlobalAdaptor()
    {
        static::$adaptors['EnvSuperGlobal'] = true;
    }

    /**
     * Method for disable EnvSuperGlobal Adaptor
     * @return void
     */
    public static function disableEnvSuperGlobalAdaptor()
    {
        static::$adaptors['EnvSuperGlobal'] = false;
    }

    /**
     * Method for enable GetEnv Adaptor
     * @return void
     */
    public static function enableGetEnvAdaptor()
    {
        static::$adaptors['GetEnv'] = true;
    }

    /**
     * Method for disable GetEnv Adaptor
     * @return void
     */
    public static function disableGetEnvAdaptor()
    {
        static::$adaptors['GetEnv'] = false;
    }

    /**
     * Method for enable ServerSuperGlobal Adaptor
     * @return void
     */
    public static function enableServerSuperGlobalAdaptor()
    {
        static::$adaptors['ServerSuperGlobal'] = true;
    }

    /**
     * Method for disable ServerSuperGlobal Adaptor
     * @return void
     */
    public static function disableServerSuperGlobalAdaptor()
    {
        static::$adaptors['ServerSuperGlobal'] = false;
    }
}
