<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Exception;

use Exception;

/**
 * Class Runtime
 * @package DotEnv\Exception
 */
class Runtime extends Exception
{
    /**
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct($message, E_WARNING);
    }
}
