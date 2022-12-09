<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

use DotEnv\Exception\Runtime;

/**
 * Interface IConverter
 * @package DotEnv\Utils
 */
interface IConverter
{
    /**
     * Method for get convert
     * @return array{0:string,1:callable|string}
     */
    public function getConverter();

    /**
     * Method for set convert
     * @param  string $convert Convert const
     * @param  callable|null $callback Callback only TO_CUSTOM
     * @return $this
     * @throws Runtime
     */
    public function setConverter($convert, $callback = null);

    /**
     * Method for clear converter
     * @return void
     */
    public function clearConverter();

    /**
     * Method for enable custom converter value
     * @param  callable $callback function($value) { return mixed; }
     * @return void
     */
    public function toCustom($callback);

    /**
     * Method for enable converter value as string
     * @return void
     */
    public function toString();

    /**
     * Method for enable converter value as string or null
     * @return void
     */
    public function toStringOrNull();

    /**
     * Method for enable converter value as bool
     * @return void
     */
    public function toBool();

    /**
     * Method for enable converter value as bool or null
     * @return void
     */
    public function toBoolOrNull();

    /**
     * Method for enable converter value as int
     * @return void
     */
    public function toInt();

    /**
     * Method for enable converter value as int or null
     * @return void
     */
    public function toIntOrNull();

    /**
     * Method for enable converter value as float
     * @return void
     */
    public function toFloat();

    /**
     * Method for enable converter value as float or null
     * @return void
     */
    public function toFloatOrNull();
}
