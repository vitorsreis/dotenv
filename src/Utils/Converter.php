<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

/**
 * class Converter
 * @package DotEnv\Utils
 */
class Converter
{
    const TO_CUSTOM         = 'toCustom';
    const TO_STRING         = 'toString';
    const TO_STRING_OR_NULL = 'toStringOrNull';
    const TO_BOOL           = 'toBool';
    const TO_BOOL_OR_NULL   = 'toBoolOrNull';
    const TO_INT            = 'toInt';
    const TO_INT_OR_NULL    = 'toIntOrNull';
    const TO_FLOAT          = 'toFloat';
    const TO_FLOAT_OR_NULL  = 'toFloatOrNull';

    /**
     * @var array{0:string,1:callable|string} $converter
     */
    private $converter = null;

    /**
     * Method for get convert
     * @return array{0:string,1:callable|string}
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * Method for clear converter
     * @return void
     */
    public function clearConverter()
    {
        $this->converter = null;
    }

    /**
     * Method for enable custom converter value
     * @param  callable $converter function($value) { return mixed; }
     * @return void
     */
    public function toCustom($converter)
    {
        $this->converter = [ static::TO_CUSTOM, $converter ];
    }

    /**
     * Method for enable converter value as string
     * @return void
     */
    public function toString()
    {
        $this->converter = [ static::TO_STRING ];
    }

    /**
     * Method for enable converter value as string or null
     * @return void
     */
    public function toStringOrNull()
    {
        $this->converter = [ static::TO_STRING_OR_NULL ];
    }

    /**
     * Method for enable converter value as bool
     * @return void
     */
    public function toBool()
    {
        $this->converter = [ static::TO_BOOL ];
    }

    /**
     * Method for enable converter value as bool or null
     * @return void
     */
    public function toBoolOrNull()
    {
        $this->converter = [ static::TO_BOOL_OR_NULL ];
    }

    /**
     * Method for enable converter value as int
     * @return void
     */
    public function toInt()
    {
        $this->converter = [ static::TO_INT ];
    }

    /**
     * Method for enable converter value as int or null
     * @return void
     */
    public function toIntOrNull()
    {
        $this->converter = [ static::TO_INT_OR_NULL ];
    }

    /**
     * Method for enable converter value as float
     * @return void
     */
    public function toFloat()
    {
        $this->converter = [ static::TO_FLOAT ];
    }

    /**
     * Method for enable converter value as float or null
     * @return void
     */
    public function toFloatOrNull()
    {
        $this->converter = [ static::TO_FLOAT_OR_NULL ];
    }
}
