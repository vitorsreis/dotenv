<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

use DotEnv\DotEnv;
use DotEnv\Exception\Runtime;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * class Rule
 * @package DotEnv\Utils
 */
class Rule
{
    /**
     * @var string Scope IS_REQUIRED:bool
     */
    const IS_REQUIRED     = 'isRequired';

    /**
     * @var string Scope IS_CUSTOM:callable|callable[]
     */
    const IS_CUSTOM       = 'isCustom';

    /**
     * @var string Scope IS_REGEX:string|null
     */
    const IS_REGEX        = 'isRegex';

    /**
     * @var string Scope IS_NOT_ALLOW:bool
     */
    const IS_NOT_ALLOW    = 'isNotAllow';

    /**
     * @var string Scope IS_BOOL:bool
     */
    const IS_BOOL         = 'isBool';

    /**
     * @var string Scope IS_INT:bool
     */
    const IS_INT          = 'isInt';

    /**
     * @var string Scope IS_FLOAT:bool
     */
    const IS_FLOAT        = 'isFloat';

    /**
     * @var string Scope IS_MIN_LENGTH:int|float|null
     */
    const IS_MIN_VALUE    = 'isMinValue';

    /**
     * @var string Scope IS_MAX_VALUE:int|float|null
     */
    const IS_MAX_VALUE    = 'isMaxValue';

    /**
     * @var string Scope IS_RANGE_VALUE:array{ 0|min:int|float|null, 1|max:int|float|null }
     */
    const IS_RANGE_VALUE  = 'isRangeValue';

    /**
     * @var string Scope IS_STRING:bool
     */
    const IS_STRING       = 'isString';

    /**
     * @var string Scope IS_MIN_LENGTH:int|float|null
     */
    const IS_MIN_LENGTH   = 'isMinLength';

    /**
     * @var string Scope IS_MAX_LENGTH:int|float|null
     */
    const IS_MAX_LENGTH   = 'isMaxLength';

    /**
     * @var string Scope IS_RANGE_LENGTH:array{ 0|min:int|float|null, 1|max:int|float|null }
     */
    const IS_RANGE_LENGTH = 'isRangeLength';

    /**
     * @var string Scope IS_EMPTY:bool
     */
    const IS_EMPTY        = 'isEmpty';

    /**
     * @var string Scope IS_NOT_EMPTY:bool
     */
    const IS_NOT_EMPTY    = 'isNotEmpty';

    /**
     * @var string Scope IS_NULL:bool
     */
    const IS_NULL         = 'isNull';

    /**
     * @var string Scope IS_NOT_NULL:bool
     */
    const IS_NOT_NULL     = 'isNotNull';

    /**
     * @var string Scope IS_EMAIL:bool
     */
    const IS_EMAIL        = 'isEmail';

    /**
     * @var string Scope IS_IP:bool
     */
    const IS_IP           = 'isIp';

    /**
     * @var string Scope IS_IPV4:bool
     */
    const IS_IPV4         = 'isIpv4';

    /**
     * @var string Scope IS_IPV6:bool
     */
    const IS_IPV6         = 'isIpv6';

    /**
     * @var string Scope IS_MAC:bool
     */
    const IS_MAC          = 'isMac';

    /**
     * @var string Scope IS_URL:bool
     */
    const IS_URL          = 'isUrl';

    /**
     * @var array Rules
     */
    private $rules;

    /**
     * @param  array $rules Rules
     * @throws Runtime
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
     * @param  array $rules Rules
     * @return $this
     * @throws Runtime
     */
    public function setRules($rules = [])
    {
        if ($rules && is_array($rules)) {
            foreach ($rules as $rule => $value) {
                # NOT DEFINED VALUE FOR BOOL RULES
                if (is_numeric($rule)) {
                    if (in_array($value, (new \ReflectionClass($this))->getConstants())) {
                        $rule = $value;
                        $value = true;
                    } elseif (is_string($value)) {
                        $rule = $value;
                    }
                }

                switch ($rule) {
                    case static::IS_CUSTOM:
                        if (!isset($this->rules[$rule])) {
                            $this->rules[$rule] = [];
                        }
                        foreach (is_array($value) ? $value : [ $value ] as $i) {
                            if (is_callable($i)) {
                                $this->rules[$rule][] = $i;
                            }
                        }
                        break;

                    case static::IS_REGEX:
                        if (!isset($this->rules[$rule])) {
                            $this->rules[$rule] = [];
                        }
                        $this->rules[$rule][] = is_string($value) ? $value : null;
                        break;

                    case static::IS_RANGE_VALUE:
                        if (isset($value['min']) || isset($value['max'])) {
                            $this->rules[static::IS_MIN_VALUE] = isset($value['min']) ? $value['min'] : null;
                            $this->rules[static::IS_MAX_VALUE] = isset($value['max']) ? $value['max'] : null;
                        } else {
                            $this->rules[static::IS_MIN_VALUE] = isset($value[0]) ? $value[0] : null;
                            $this->rules[static::IS_MAX_VALUE] = isset($value[1]) ? $value[1] : null;
                        }
                        break;

                    case static::IS_MIN_VALUE:
                    case static::IS_MAX_VALUE:
                        $this->rules[$rule] = is_numeric($value) ? $value : null;
                        break;

                    case static::IS_RANGE_LENGTH:
                        if (isset($value['min']) || isset($value['max'])) {
                            $this->rules[static::IS_MIN_LENGTH] = isset($value['min']) ? $value['min'] : null;
                            $this->rules[static::IS_MAX_LENGTH] = isset($value['max']) ? $value['max'] : null;
                        } else {
                            $this->rules[static::IS_MIN_LENGTH] = isset($value[0]) ? $value[0] : null;
                            $this->rules[static::IS_MAX_LENGTH] = isset($value[1]) ? $value[1] : null;
                        }
                        break;

                    case static::IS_MIN_LENGTH:
                    case static::IS_MAX_LENGTH:
                        $this->rules[$rule] = is_int($value) ? $value : null;
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
                        $this->rules[$rule] = (bool)$value;
                        break;

                    default:
                        if (DotEnv::isDebug()) {
                            throw new Runtime("Rule \"$rule\" not implemented!");
                        }
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
     * @param  callable $callback function($value) { return bool; }
     * @return $this
     */
    public function isCustom($callback)
    {
        if (!isset($this->rules[static::IS_CUSTOM])) {
            $this->rules[static::IS_CUSTOM] = [];
        }
        $this->rules[static::IS_CUSTOM][] = is_callable($callback) ? $callback : null;
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
