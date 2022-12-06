<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Common;

use DotEnv\Utils\Rule;

/**
 * Trait Rules
 * @package DotEnv\Common
 */
trait Rules
{
    /**
     * @var Rule[] Rules instance
     */
    private $rules = [];

    /**
     * Method for add env key rule
     * @param string      ...$keys Env Keys
     * @return Rule
     */
    public function rule(...$keys)
    {
        $rules = new Rule();
        foreach ($keys as $key) {
            if (isset($this->rules[$key])) {
                unset($this->rules[$key]);
            }

            $this->rules[$key] = &$rules;
        }

        return $rules;
    }

    /**
     * Method for remove env key rule
     * @param  string ...$keys Env keys
     * @return $this
     */
    public function removeRule(...$keys)
    {
        foreach ($keys as $key) {
            unset($this->rules[$key]);
        }
        return $this;
    }

    /**
     * Method for clear rules
     * @return void
     */
    public function clearRules()
    {
        $this->rules = [];
    }

    /**
     * Method for run env key invalidation
     * @param  string       $key   Env key
     * @param  mixed        $value Env value
     * @return string|false        Rule or "false" if success
     */
    protected function invalidate($key, $value)
    {
        if (!isset($this->rules[$key]) || !($rules = $this->rules[$key]->getRules())) {
            return false;
        }

        foreach ($rules as $ruleKey => $ruleValue) {
            switch ($ruleKey) {
                case Rule::IS_CUSTOM:
                    foreach ($ruleValue as $pos => $rule) {
                        if ($rule($value)) {
                            continue 3;
                        } else {
                            return "$ruleKey\[$pos]";
                        }
                    }
                    break;
                case Rule::IS_REGEX:
                    foreach ($ruleValue as $pos => $regex) {
                        if (filter_var(
                            $value,
                            FILTER_VALIDATE_REGEXP,
                            [ 'flags' => FILTER_NULL_ON_FAILURE, 'options' => [ 'regexp' => $regex ] ]
                        ) !== null) {
                            continue 3;
                        } else {
                            return "$ruleKey\[$pos]";
                        }
                    }
                    break;
                case Rule::IS_REQUIRED:
                    continue 2;
                case Rule::IS_NOT_ALLOW:
                    break;
                case Rule::IS_BOOL:
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_INT:
                    if (filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_FLOAT:
                    if (filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_MIN_VALUE:
                    if ($value >= $ruleValue) {
                        continue 2;
                    }
                    break;
                case Rule::IS_MAX_VALUE:
                    if ($value <= $ruleValue) {
                        continue 2;
                    }
                    break;
                case Rule::IS_STRING:
                    if (is_string($value)) {
                        continue 2;
                    }
                    break;
                case Rule::IS_MIN_LENGTH:
                    if (strlen($value) >= $ruleValue) {
                        continue 2;
                    }
                    break;
                case Rule::IS_MAX_LENGTH:
                    if (strlen($value) <= $ruleValue) {
                        continue 2;
                    }
                    break;
                case Rule::IS_EMPTY:
                    if (empty($value)) {
                        continue 2;
                    }
                    break;
                case Rule::IS_NOT_EMPTY:
                    if (!empty($value)) {
                        continue 2;
                    }
                    break;
                case Rule::IS_NULL:
                    if ($value === null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_NOT_NULL:
                    if ($value !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_EMAIL:
                    if (filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_IP:
                    if (filter_var($value, FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE) !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_IPV4:
                    if (filter_var($value, FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_IPV4) !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_IPV6:
                    if (filter_var($value, FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_IPV6) !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_MAC:
                    if (filter_var($value, FILTER_VALIDATE_MAC, FILTER_NULL_ON_FAILURE) !== null) {
                        continue 2;
                    }
                    break;
                case Rule::IS_URL:
                    if (filter_var($value, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE) !== null) {
                        continue 2;
                    }
                    break;
            }
            return $ruleKey;
        }
        return false;
    }

    /**
     * Method for check all required env keys
     * @return string|false Required key or "false" if success
     */
    protected function invalidateRequiredList()
    {
        foreach (array_keys(array_filter($this->rules, function ($rule) {
            return in_array(Rule::IS_REQUIRED, $rule->getRules());
        })) as $envKey) {
            if (!array_key_exists($envKey, $this->memory)) {
                return $envKey;
            }
        }
        return false;
    }
}
