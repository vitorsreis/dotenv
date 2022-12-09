<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

use DotEnv\Exception\Runtime;

/**
 * Interface IRule
 * @package DotEnv\Utils
 */
interface IRule
{
    /**
     * Method for get rules
     * @return array
     */
    public function getRules();

    /**
     * Method for set rule
     * @param  string $rule Rule Key
     * @param  mixed  $value Rule Value
     * @return $this
     * @throws Runtime
     */
    public function setRule($rule, $value = null);

    /**
     * Method for set rules
     * @param  array $rules Rules
     * @return $this
     * @throws Runtime
     */
    public function setRules($rules = []);

    /**
     * Method for clear rules
     * @return $this
     */
    public function clearRules();

    /**
     * Method for set custom rule
     * @param  callable $callback function($value) { return bool; }
     * @return $this
     */
    public function isCustom($callback);

    /**
     * Method for set regex valid string rule
     * @param  string|null $regex Set regex string for validate, "null" for disable
     * @return $this
     */
    public function isRegex($regex);

    /**
     * Method for enable/disable is required rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isRequired($status = true);

    /**
     * Method for enable/disable is not allow rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotAllow($status = true);

    /**
     * Method for enable/disable is bool rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isBool($status = true);

    /**
     * Method for enable/disable is int rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isInt($status = true);

    /**
     * Method for enable/disable is float rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isFloat($status = true);

    /**
     * Method for set min valid int/float rule
     * @param  int|float|null $value Set value as int|float for validate, "null" for disable
     * @return $this
     */
    public function isMinValue($value);

    /**
     * Method for set max valid int/float rule
     * @param  int|float|null $value Set value as int|float for validate, "null" for disable
     * @return $this
     */
    public function isMaxValue($value);

    /**
     * Method for set min/max length valid string rule
     * @param  int|float|null $min Set min value as int|float for validate, "null" for disable
     * @param  int|float|null $max Set max value as int|float for validate, "null" for disable
     * @return $this
     */
    public function isRangeValue($min, $max);

    /**
     * Method for enable/disable is string rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isString($status = true);

    /**
     * Method for set min length valid string rule
     * @param  int|float|null $value Set length as int for validate, "null" for disable
     * @return $this
     */
    public function isMinLength($value);

    /**
     * Method for set max length valid string rule
     * @param  int|float|null $value Set length as int for validate, "null" for disable
     * @return $this
     */
    public function isMaxLength($value);

    /**
     * Method for set min/max length valid string rule
     * @param  int|float|null $min Set min length as int for validate, "null" for disable
     * @param  int|float|null $max Set max length as int for validate, "null" for disable
     * @return $this
     */
    public function isRangeLength($min, $max);

    /**
     * Method for enable/disable is empty rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isEmpty($status = true);

    /**
     * Method for enable/disable is not empty rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotEmpty($status = true);

    /**
     * Method for enable/disable is null rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNull($status = true);

    /**
     * Method for enable/disable is not null rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotNull($status = true);

    /**
     * Method for enable/disable is email rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isEmail($status = true);

    /**
     * Method for enable/disable is IP rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIp($status = true);

    /**
     * Method for enable/disable is IPv4 rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIpv4($status = true);

    /**
     * Method for enable/disable is IPv6 rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIpv6($status = true);

    /**
     * Method for enable/disable is MAC rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isMac($status = true);

    /**
     * Method for enable/disable is URL rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isUrl($status = true);
}
