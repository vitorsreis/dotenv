<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv\Adaptor;

use DotEnv\Utils\IAdaptor;

/**
 * Class EnvSuperGlobal Adaptor
 *
 * @package DotEnv\Adaptor
 * @author  Vitor Reis <vitor@d5w.com.br>
 */
class EnvSuperGlobal implements IAdaptor
{
    /**
     * Method for adaptor put value
     * @param  string $key   Env Key
     * @param  string $value Env Value
     * @return bool          If success "true", else "false"
     */
    public static function put($key, $value)
    {
        $_ENV[$key] = $value;
        return true;
    }
}
