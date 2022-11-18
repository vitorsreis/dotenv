<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv\Exception;

use Exception;

/**
 * Class Loader
 *
 * @package DotEnv\Exception
 * @author  Vitor Reis <vitor@d5w.com.br>
 */
class Loader extends Exception
{
    /**
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct($message, E_WARNING);
    }
}
