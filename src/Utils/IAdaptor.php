<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Utils;

use DotEnv\Exception\Runtime;

/**
 * Interface IAdaptor
 * @package DotEnv\Utils
 */
interface IAdaptor
{
    /**
     * Method for adaptor put value
     * @param  string $key   Env Key
     * @param  mixed  $value Env Value
     * @return bool          If success "true", else "false"
     * @throws Runtime
     */
    public static function put($key, $value);
}
