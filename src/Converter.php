<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv;

use DotEnv\Exception\Runtime;
use DotEnv\Utils\IConverter;
use ReflectionClass;

/**
 * class Converter
 * @package DotEnv
 */
class Converter implements IConverter
{
    /**
     * @var string Scope &lt;callable&gt;
     */
    const TO_CUSTOM         = 'Convert:toCustom';

    /**
     * @var string Scope TO_STRING
     */
    const TO_STRING         = 'Convert:toString';

    /**
     * @var string Scope TO_STRING_OR_NULL
     */
    const TO_STRING_OR_NULL = 'Convert:toStringOrNull';

    /**
     * @var string Scope TO_BOOL
     */
    const TO_BOOL           = 'Convert:toBool';

    /**
     * @var string Scope TO_BOOL_OR_NULL
     */
    const TO_BOOL_OR_NULL   = 'Convert:toBoolOrNull';

    /**
     * @var string Scope TO_INT
     */
    const TO_INT            = 'Convert:toInt';

    /**
     * @var string Scope TO_INT_OR_NULL
     */
    const TO_INT_OR_NULL    = 'Convert:toIntOrNull';

    /**
     * @var string Scope TO_FLOAT
     */
    const TO_FLOAT          = 'Convert:toFloat';

    /**
     * @var string Scope TO_FLOAT_OR_NULL
     */
    const TO_FLOAT_OR_NULL  = 'Convert:toFloatOrNull';

    /**
     * @var array|null Class Constants
     */
    private static $reflectionConstants = null;

    /**
     * @var array{0:string,1:callable|string} $converter
     */
    private $converter = null;

    /**
     * Method for get class constants
     * @return string[]
     */
    public static function __constants()
    {
        # MEMORY PREVENT
        if (is_null(static::$reflectionConstants)) {
            static::$reflectionConstants = (new ReflectionClass(__CLASS__))->getConstants();
        }
        return static::$reflectionConstants;
    }

    /**
     * Method for get convert
     * @return array{0:string,1:callable|string}
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * Method for set convert
     * @param  string        $convert  Convert const
     * @param  callable|null $callback Callback only TO_CUSTOM
     * @return $this
     * @throws Runtime
     */
    public function setConverter($convert, $callback = null)
    {
        switch ($convert) {
            case static::TO_CUSTOM:
                if (is_callable($callback)) {
                    $this->converter = [ $convert, $callback ];
                }
                break;

            case static::TO_STRING:
            case static::TO_STRING_OR_NULL:
            case static::TO_BOOL:
            case static::TO_BOOL_OR_NULL:
            case static::TO_INT:
            case static::TO_INT_OR_NULL:
            case static::TO_FLOAT:
            case static::TO_FLOAT_OR_NULL:
                $this->converter = [ $convert ];
                break;

            default:
                if (DotEnv::isDebug()) {
                    throw new Runtime("Convert \"$convert\" not implemented!");
                }
                break;
        }
        return $this;
    }

    /**
     * Method for clear converter
     * @return $this
     */
    public function clearConverter()
    {
        $this->converter = null;
        return $this;
    }

    /**
     * Method for enable custom converter value
     * @param  callable $callback function($value) { return mixed; }
     * @return void
     */
    public function toCustom($callback)
    {
        $this->converter = [ static::TO_CUSTOM, $callback ];
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
