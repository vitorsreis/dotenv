<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

use DotEnv\Exception\Runtime;

/**
 * class ConverterSetter
 * @package DotEnv\Utils
 */
class ConverterSetter extends Setter implements IConverter
{
    /**
     * Method for get converter
     * @return null
     */
    public function getConverter()
    {
        return null;
    }

    /**
     * Method for set converter
     * @param  string        $convert  Convert const
     * @param  callable|null $callback Callback only TO_CUSTOM
     * @return $this
     * @throws Runtime
     */
    public function setConverter($convert, $callback = null)
    {
        $this->invoke('setConverter', [ $convert, $callback ]);
        return $this;
    }

    /**
     * Method for clear converter
     * @return $this
     */
    public function clearConverter()
    {
        $this->invoke('clearConverter');
        return $this;
    }

    /**
     * Method for enable custom converter value
     * @param callable $callback function($value) { return mixed; }
     * @return void
     */
    public function toCustom($callback)
    {
        $this->invoke('toCustom', [ $callback ]);
    }

    /**
     * Method for enable converter value as string
     * @return void
     */
    public function toString()
    {
        $this->invoke('toString');
    }

    /**
     * Method for enable converter value as string or null
     * @return void
     */
    public function toStringOrNull()
    {
        $this->invoke('toStringOrNull');
    }

    /**
     * Method for enable converter value as bool
     * @return void
     */
    public function toBool()
    {
        $this->invoke('toBool');
    }

    /**
     * Method for enable converter value as bool or null
     * @return void
     */
    public function toBoolOrNull()
    {
        $this->invoke('toBoolOrNull');
    }

    /**
     * Method for enable converter value as int
     * @return void
     */
    public function toInt()
    {
        $this->invoke('toInt');
    }

    /**
     * Method for enable converter value as int or null
     * @return void
     */
    public function toIntOrNull()
    {
        $this->invoke('toIntOrNull');
    }

    /**
     * Method for enable converter value as float
     * @return void
     */
    public function toFloat()
    {
        $this->invoke('toFloat');
    }

    /**
     * Method for enable converter value as float or null
     * @return void
     */
    public function toFloatOrNull()
    {
        $this->invoke('toFloatOrNull');
    }
}
