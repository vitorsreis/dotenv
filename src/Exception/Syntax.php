<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Exception;

use Exception;

/**
 * Class Syntax
 * @package DotEnv\Exception
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
