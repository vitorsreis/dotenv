<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

/**
 * class Setter
 * @package DotEnv\Utils
 */
class Setter
{
    /**
     * @var array Listeners
     */
    private $parents;

    /**
     * @param array $parents Parents
     */
    public function __construct($parents = [])
    {
        $this->parents = $parents;
    }

    /**
     * Method for invoke method in all listeners
     * @param  string $method
     * @param  array  $args
     * @return array
     */
    protected function invoke($method, $args = [])
    {
        $results = [];

        foreach ($this->parents as $listener) {
            $results = call_user_func_array([ $listener, $method ], $args);
        }

        return $results;
    }
}
