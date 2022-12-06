<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Adaptor;

use DotEnv\DotEnv;
use DotEnv\Exception\Runtime;
use DotEnv\Utils\IAdaptor;

/**
 * Class Constant Adaptor <b>Not recommended</b>
 * @package DotEnv\Adaptor
 */
class Constant implements IAdaptor
{
    /**
     * Method for adaptor put value
     * @param  string $key   Env Key
     * @param  mixed  $value Env Value
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

        return define($key, $value, false);
    }
}
