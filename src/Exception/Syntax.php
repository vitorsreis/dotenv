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
 * Class Syntax
 *
 * @package DotEnv\Exception
 * @author  Vitor Reis <vitor@d5w.com.br>
 */
class Syntax extends Exception
{
    /**
     * @param string      $message
     * @param string|null $file
     * @param int|null    $line
     */
    public function __construct($message = "", $file = null, $line = null)
    {
        parent::__construct($message, E_WARNING);

        if (!is_null($file)) {
            $this->file = $file;
        }

        if (!is_null($line)) {
            $this->line = $line;
        }
    }
}
