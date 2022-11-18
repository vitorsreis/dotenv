<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv\Adaptor;

use DotEnv\DotEnv;
use DotEnv\Exception\Runtime;
use DotEnv\Utils\IAdaptor;

/**
 * Class Constant Adaptor <b>Not recommended</b>
 *
 * @package DotEnv\Adaptor
 * @author  Vitor Reis <vitor@d5w.com.br>
 */
class Constant implements IAdaptor
{
    /**
     * Method for adaptor put value
     * @param  string $key   Env Key
     * @param  string $value Env Value
     * @return bool          If success "true", else "false"
     * @throws Runtime
     */
    public static function put($key, $value)
    {
        if (defined($key)) {
            if (DotEnv::isDebug()) {
                throw new Runtime("Constant \"$key\" already exists");
            } else {
                return false;
            }
        }

        return define($key, $value);
    }
}
