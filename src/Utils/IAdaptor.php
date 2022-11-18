<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv\Utils;

use DotEnv\Exception\Runtime;

/**
 * Interface IAdaptor
 *
 * @package DotEnv\Utils
 * @author  Vitor Reis <vitor@d5w.com.br>
 */
interface IAdaptor
{
    /**
     * Method for adaptor put value
     * @param  string $key   Env Key
     * @param  string $value Env Value
     * @return bool          If success "true", else "false"
     * @throws Runtime
     */
    public static function put($key, $value);
}
