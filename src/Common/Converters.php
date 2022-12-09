<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Common;

use DotEnv\Converter;
use DotEnv\Exception\Loader;
use DotEnv\Utils\ConverterSetter;

/**
 * Trait Converters
 * @package DotEnv\Common
 */
trait Converters
{
    /**
     * @var Converter[] Converters
     */
    private $converters = [];

    /**
     * Method for add env key convert
     * @param  string ...$keys Env Keys
     * @return ConverterSetter
     */
    public function convert(...$keys)
    {
        $parents = [];

        foreach ($keys as $key) {
            if (!isset($this->converters[$key])) {
                $this->converters[$key] = new Converter();
            }

            $parents[] = &$this->converters[$key];
        }

        return new ConverterSetter($parents);
    }

    /**
     * Method for get converters
     * @return string[]
     */
    public function getConverters()
    {
        return array_map(function ($converter) {
            return $converter->getConverter();
        }, $this->converters);
    }

    /**
     * Method for remove env key filter
     * @param  string ...$keys Env keys
     * @return $this
     */
    public function removeConverter(...$keys)
    {
        foreach ($keys as $key) {
            unset($this->converters[$key]);
        }
        return $this;
    }

    /**
     * Method for clear filters
     * @return void
     */
    public function clearConverters()
    {
        $this->converters = [];
    }

    /**
     * Method for convert env value
     * @param  string $key   Env key
     * @param  string $value Env value
     * @return mixed         Env value converted
     * @throws Loader
     */
    protected function converter($key, &$value)
    {
        if (isset($this->converters[$key]) && ($converter = $this->converters[$key]->getConverter())) {
            switch (isset($converter[0]) ? $converter[0] : null) {
                case Converter::TO_CUSTOM:
                    $value = isset($converter[1]) && is_callable($converter[1])
                        ? call_user_func_array($converter[1], [ $value ])
                        : null;
                    break;
                case Converter::TO_STRING:
                case Converter::TO_STRING_OR_NULL:
                    $value = strval($value);
                    break;
                case Converter::TO_BOOL:
                case Converter::TO_BOOL_OR_NULL:
                    if (($value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) === null) {
                        if ($converter[0] !== Converter::TO_BOOL_OR_NULL && static::isDebug()) {
                            throw new Loader("Value \"$key\" is not type \"Boolean\"!");
                        }
                    }
                    break;
                case Converter::TO_INT:
                case Converter::TO_INT_OR_NULL:
                    if (($value = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE)) === null) {
                        if ($converter[0] !== Converter::TO_INT_OR_NULL && static::isDebug()) {
                            throw new Loader("Value \"$key\" is not type \"Integer\"!");
                        }
                    }
                    break;
                case Converter::TO_FLOAT:
                case Converter::TO_FLOAT_OR_NULL:
                    if (($value = filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE)) === null) {
                        if ($converter[0] !== Converter::TO_FLOAT_OR_NULL && static::isDebug()) {
                            throw new Loader("Value \"$key\" is not type \"Float\"!");
                        }
                    }
                    break;
            }
        }
        return $value;
    }
}
