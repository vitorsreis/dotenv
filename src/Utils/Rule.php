<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

/**
 * class Rule
 * @package DotEnv\Utils
 */
class Rule
{
    const IS_CUSTOM       = 'isCustom';
    const IS_REGEX        = 'isRegex';
    const IS_REQUIRED     = 'isRequired';
    const IS_NOT_ALLOW    = 'isNotAllow';
    const IS_BOOL         = 'isBool';
    const IS_INT          = 'isInt';
    const IS_FLOAT        = 'isFloat';
    const IS_MIN_VALUE    = 'isMinValue';
    const IS_MAX_VALUE    = 'isMaxValue';
    const IS_RANGE_VALUE  = 'isRangeValue';
    const IS_STRING       = 'isString';
    const IS_MIN_LENGTH   = 'isMinLength';
    const IS_MAX_LENGTH   = 'isMaxLength';
    const IS_RANGE_LENGTH = 'isRangeLength';
    const IS_EMPTY        = 'isEmpty';
    const IS_NOT_EMPTY    = 'isNotEmpty';
    const IS_NULL         = 'isNull';
    const IS_NOT_NULL     = 'isNotNull';
    const IS_EMAIL        = 'isEmail';
    const IS_IP           = 'isIp';
    const IS_IPV4         = 'isIpv4';
    const IS_IPV6         = 'isIpv6';
    const IS_MAC          = 'isMac';
    const IS_URL          = 'isUrl';

    /**
     * @var array Rules
     */
    private $rules;

    /**
     * @param array{
     *     IS_CUSTOM:callable|callable[],
     *     IS_REGEX:string|null,
     *     IS_REQUIRED:bool,
     *     IS_NOT_ALLOW:bool,
     *     IS_BOOL:bool,
     *     IS_INT:bool,
     *     IS_FLOAT:bool,
     *     IS_MIN_LENGTH:int|float|null,
     *     IS_MAX_VALUE:int|float|null,
     *     IS_RANGE_VALUE:array{min:int|float|null,max:int|float|null},
     *     IS_STRING:bool,
     *     IS_MIN_LENGTH:int|null,
     *     IS_MAX_LENGTH:int|null,
     *     IS_RANGE_LENGTH:array{min:int|null,max:int|null},
     *     IS_EMPTY:bool,
     *     IS_NOT_EMPTY:bool,
     *     IS_NULL:bool,
     *     IS_NOT_NULL:bool,
     *     IS_EMAIL:bool,
     *     IS_IP:bool,
     *     IS_IPV4:bool,
     *     IS_IPV6:bool,
     *     IS_MAC:bool,
     *     IS_URL:bool
     * } $rules Rules
     */
    public function __construct($rules = [])
    {
        $this->setRules($rules);
    }

    /**
     * Method for get rules
     * @return array
     */
    public function getRules()
    {
        return $this->rules = array_filter($this->rules, function ($value) {
            return !is_null($value) && $value !== false;
        });
    }

    /**
     * Method for set rules
     * @param array{
     *     IS_CUSTOM:callable|callable[],
     *     IS_REGEX:string|null,
     *     IS_REQUIRED:bool,
     *     IS_NOT_ALLOW:bool,
     *     IS_BOOL:bool,
     *     IS_INT:bool,
     *     IS_FLOAT:bool,
     *     IS_MIN_LENGTH:int|float|null,
     *     IS_MAX_VALUE:int|float|null,
     *     IS_RANGE_VALUE:array{min:int|float|null,max:int|float|null},
     *     IS_STRING:bool,
     *     IS_MIN_LENGTH:int|null,
     *     IS_MAX_LENGTH:int|null,
     *     IS_RANGE_LENGTH:array{min:int|null,max:int|null},
     *     IS_EMPTY:bool,
     *     IS_NOT_EMPTY:bool,
     *     IS_NULL:bool,
     *     IS_NOT_NULL:bool,
     *     IS_EMAIL:bool,
     *     IS_IP:bool,
     *     IS_IPV4:bool,
     *     IS_IPV6:bool,
     *     IS_MAC:bool,
     *     IS_URL:bool
     * } $rules Rules
     * @return $this
     */
    public function setRules($rules = [])
    {
        if ($rules && is_array($rules)) {
            foreach ($rules as $key => $value) {
                switch ($key) {
                    case static::IS_CUSTOM:
                        if (!isset($this->rules[$key])) {
                            $this->rules[$key] = [];
                        }
                        foreach (is_array($value) ? $value : [ $value ] as $i) {
                            if (is_callable($i)) {
                                $this->rules[$key][] = $i;
                            }
                        }
                        break;

                    case static::IS_REGEX:
                        if (!isset($this->rules[$key])) {
                            $this->rules[$key] = [];
                        }
                        $this->rules[$key][] = is_string($value) ? $value : null;
                        break;

                    case static::IS_RANGE_VALUE:
                        $this->rules[static::IS_MIN_VALUE] = isset($value['min']) ? $value['min'] : null;
                        $this->rules[static::IS_MAX_VALUE] = isset($value['max']) ? $value['max'] : null;
                        break;

                    case static::IS_MIN_VALUE:
                    case static::IS_MAX_VALUE:
                        $this->rules[$key] = is_numeric($value) ? $value : null;
                        break;

                    case static::IS_RANGE_LENGTH:
                        $this->rules[static::IS_MIN_LENGTH] = isset($value['min']) ? $value['min'] : null;
                        $this->rules[static::IS_MAX_LENGTH] = isset($value['max']) ? $value['max'] : null;
                        break;

                    case static::IS_MIN_LENGTH:
                    case static::IS_MAX_LENGTH:
                        $this->rules[$key] = is_int($value) ? $value : null;
                        break;

                    case static::IS_REQUIRED:
                    case static::IS_NOT_ALLOW:
                    case static::IS_BOOL:
                    case static::IS_INT:
                    case static::IS_FLOAT:
                    case static::IS_STRING:
                    case static::IS_EMPTY:
                    case static::IS_NOT_EMPTY:
                    case static::IS_NULL:
                    case static::IS_NOT_NULL:
                    case static::IS_EMAIL:
                    case static::IS_IP:
                    case static::IS_IPV4:
                    case static::IS_IPV6:
                    case static::IS_MAC:
                    case static::IS_URL:
                        $this->rules = (bool)$value;
                        break;
                }
            }
        } else {
            $this->rules = [];
        }
        return $this;
    }

    /**
     * Method for clear rules
     * @return $this
     */
    public function clearRules()
    {
        $this->rules = [];
        return $this;
    }

    /**
     * Method for set custom rule
     * @param  callable $validator function($value) { return bool; }
     * @return $this
     */
    public function isCustom($validator)
    {
        if (!isset($this->rules[static::IS_CUSTOM])) {
            $this->rules[static::IS_CUSTOM] = [];
        }
        $this->rules[static::IS_CUSTOM][] = is_callable($validator) ? $validator : null;
        return $this;
    }

    /**
     * Method for set regex valid string rule
     * @param  string|null $regex Set regex string for validate, "null" for disable
     * @return $this
     */
    public function isRegex($regex)
    {
        if (!isset($this->rules[static::IS_REGEX])) {
            $this->rules[static::IS_REGEX] = [];
        }
        $this->rules[static::IS_REGEX][] = is_string($regex) ? $regex : null;
        return $this;
    }

    /**
     * Method for enable/disable is required rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isRequired($status = true)
    {
        $this->rules[static::IS_REQUIRED] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is not allow rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotAllow($status = true)
    {
        $this->rules[static::IS_NOT_ALLOW] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is bool rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isBool($status = true)
    {
        $this->rules[static::IS_BOOL] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is int rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isInt($status = true)
    {
        $this->rules[static::IS_INT] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is float rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isFloat($status = true)
    {
        $this->rules[static::IS_FLOAT] = (bool)$status;
        return $this;
    }

    /**
     * Method for set min valid int/float rule
     * @param  int|float|null $value Set value as int|float for validate, "null" for disable
     * @return $this
     */
    public function isMinValue($value)
    {
        $this->rules[static::IS_MIN_VALUE] = is_numeric($value) ? $value : null;
        return $this;
    }

    /**
     * Method for set max valid int/float rule
     * @param  int|float|null $value Set value as int|float for validate, "null" for disable
     * @return $this
     */
    public function isMaxValue($value)
    {
        $this->rules[static::IS_MAX_VALUE] = is_numeric($value) ? $value : null;
        return $this;
    }

    /**
     * Method for set min/max length valid string rule
     * @param  int|float|null $min Set min value as int|float for validate, "null" for disable
     * @param  int|float|null $max Set max value as int|float for validate, "null" for disable
     * @return $this
     */
    public function isRangeValue($min, $max)
    {
        $this->rules[static::IS_MIN_VALUE] = is_numeric($min) ? $min : null;
        $this->rules[static::IS_MAX_VALUE] = is_numeric($max) ? $max : null;
        return $this;
    }

    /**
     * Method for enable/disable is string rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isString($status = true)
    {
        $this->rules[static::IS_STRING] = (bool)$status;
        return $this;
    }

    /**
     * Method for set min length valid string rule
     * @param  int|float|null $value Set length as int for validate, "null" for disable
     * @return $this
     */
    public function isMinLength($value)
    {
        $this->rules[static::IS_MIN_LENGTH] = is_int($value) ? $value : null;
        return $this;
    }

    /**
     * Method for set max length valid string rule
     * @param  int|float|null $value Set length as int for validate, "null" for disable
     * @return $this
     */
    public function isMaxLength($value)
    {
        $this->rules[static::IS_MAX_LENGTH] = is_int($value) ? $value : null;
        return $this;
    }

    /**
     * Method for set min/max length valid string rule
     * @param  int|float|null $min Set min length as int for validate, "null" for disable
     * @param  int|float|null $max Set max length as int for validate, "null" for disable
     * @return $this
     */
    public function isRangeLength($min, $max)
    {
        $this->rules[static::IS_MIN_LENGTH] = is_int($min) ? $min : null;
        $this->rules[static::IS_MAX_LENGTH] = is_int($max) ? $max : null;
        return $this;
    }

    /**
     * Method for enable/disable is empty rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isEmpty($status = true)
    {
        $this->rules[static::IS_EMPTY] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is not empty rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotEmpty($status = true)
    {
        $this->rules[static::IS_NOT_EMPTY] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is null rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNull($status = true)
    {
        $this->rules[static::IS_NULL] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is not null rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotNull($status = true)
    {
        $this->rules[static::IS_NOT_NULL] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is email rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isEmail($status = true)
    {
        $this->rules[static::IS_EMAIL] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is IP rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIp($status = true)
    {
        $this->rules[static::IS_IP] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is IPv4 rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIpv4($status = true)
    {
        $this->rules[static::IS_IPV4] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is IPv6 rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIpv6($status = true)
    {
        $this->rules[static::IS_IPV6] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is MAC rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isMac($status = true)
    {
        $this->rules[static::IS_MAC] = (bool)$status;
        return $this;
    }

    /**
     * Method for enable/disable is URL rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isUrl($status = true)
    {
        $this->rules[static::IS_URL] = (bool)$status;
        return $this;
    }
}
