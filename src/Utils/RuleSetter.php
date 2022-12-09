<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

/**
 * class RuleSetter
 * @package DotEnv\Utils
 */
class RuleSetter extends Setter implements IRule
{
    /**
     * Method for get rules
     * @return null
     */
    public function getRules()
    {
        return null;
    }

    /**
     * Method for set rule
     * @param  string $rule Rule Key
     * @param  mixed  $value Rule Value
     * @return $this
     */
    public function setRule($rule, $value = null)
    {
        if (func_num_args() === 1) {
            $value = true;
        }

        $this->invoke('setRule', [ $rule, $value ]);
        return $this;
    }

    /**
     * Method for set rules
     * @param  array $rules Rules
     * @return $this
     */
    public function setRules($rules = [])
    {
        $this->invoke('setRules', [ $rules ]);
        return $this;
    }

    /**
     * Method for clear rules
     * @return $this
     */
    public function clearRules()
    {
        $this->invoke('clearRules');
        return $this;
    }

    /**
     * Method for set custom rule
     * @param  callable $callback function($value) { return bool; }
     * @return $this
     */
    public function isCustom($callback)
    {
        $this->invoke('isCustom', [ $callback ]);
        return $this;
    }

    /**
     * Method for set regex valid string rule
     * @param  string|null $regex Set regex string for validate, "null" for disable
     * @return $this
     */
    public function isRegex($regex)
    {
        $this->invoke('isRegex', [ $regex ]);
        return $this;
    }

    /**
     * Method for enable/disable is required rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isRequired($status = true)
    {
        $this->invoke('isRequired', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is not allow rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotAllow($status = true)
    {
        $this->invoke('isNotAllow', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is bool rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isBool($status = true)
    {
        $this->invoke('isBool', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is int rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isInt($status = true)
    {
        $this->invoke('isInt', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is float rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isFloat($status = true)
    {
        $this->invoke('isFloat', [ $status ]);
        return $this;
    }

    /**
     * Method for set min valid int/float rule
     * @param  int|float|null $value Set value as int|float for validate, "null" for disable
     * @return $this
     */
    public function isMinValue($value)
    {
        $this->invoke('isMinValue', [ $value ]);
        return $this;
    }

    /**
     * Method for set max valid int/float rule
     * @param  int|float|null $value Set value as int|float for validate, "null" for disable
     * @return $this
     */
    public function isMaxValue($value)
    {
        $this->invoke('isMaxValue', [ $value ]);
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
        $this->invoke('isRangeValue', [ $min, $max ]);
        return $this;
    }

    /**
     * Method for enable/disable is string rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isString($status = true)
    {
        $this->invoke('isString', [ $status ]);
        return $this;
    }

    /**
     * Method for set min length valid string rule
     * @param  int|float|null $value Set length as int for validate, "null" for disable
     * @return $this
     */
    public function isMinLength($value)
    {
        $this->invoke('isMinLength', [ $value ]);
        return $this;
    }

    /**
     * Method for set max length valid string rule
     * @param  int|float|null $value Set length as int for validate, "null" for disable
     * @return $this
     */
    public function isMaxLength($value)
    {
        $this->invoke('isMaxLength', [ $value ]);
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
        $this->invoke('isRangeLength', [ $min, $max ]);
        return $this;
    }

    /**
     * Method for enable/disable is empty rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isEmpty($status = true)
    {
        $this->invoke('isEmpty', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is not empty rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotEmpty($status = true)
    {
        $this->invoke('isNotEmpty', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is null rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNull($status = true)
    {
        $this->invoke('isNull', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is not null rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isNotNull($status = true)
    {
        $this->invoke('isNotNull', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is email rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isEmail($status = true)
    {
        $this->invoke('isEmail', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is IP rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIp($status = true)
    {
        $this->invoke('isIp', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is IPv4 rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIpv4($status = true)
    {
        $this->invoke('isIpv4', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is IPv6 rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isIpv6($status = true)
    {
        $this->invoke('isIpv6', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is MAC rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isMac($status = true)
    {
        $this->invoke('isMac', [ $status ]);
        return $this;
    }

    /**
     * Method for enable/disable is URL rule
     * @param  bool $status Enable/Disable rule
     * @return $this
     */
    public function isUrl($status = true)
    {
        $this->invoke('isUrl', [ $status ]);
        return $this;
    }
}
