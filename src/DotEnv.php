<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv;

use DotEnv\Common\Adaptors;
use DotEnv\Common\Debug;
use DotEnv\Common\Converters;
use DotEnv\Common\Parser;
use DotEnv\Common\Rules;
use DotEnv\Exception\Runtime;

/**
 * Class DotEnv
 * @package DotEnv
 */
class DotEnv
{
    use Adaptors;
    use Debug;
    use Converters;
    use Parser;
    use Rules;

    /**
     * @var string[] Memory Adaptor
     */
    private $memory = [];

    /**
     * Method for initialize Memory Adaptor
     * @param string ...$paths .env paths
     */
    public function __construct(...$paths)
    {
        $this->memory = $_ENV;

        # CONSTRUCT LOAD
        if ($paths) {
            $this->load(...$paths);
        }
    }

    /**
     * Method for get env value by key in Memory Adaptor
     * @param  string $key Env Key
     * @return string|null If success "string", else "null"
     * @throws Runtime
     */
    public function get($key)
    {
        if (!isset($this->memory[$key])) {
            if (static::isDebug()) {
                throw new Runtime("Key \"$key\" not found");
            } else {
                return null;
            }
        }

        return $this->memory[$key];
    }

    /**
     * Method for put env key/value in all enabled adaptors
     * @param  string $key   Env Key
     * @param  string $value Env Value
     * @return bool          If success "true", else "false"
     * @throws Runtime
     */
    public function put($key, $value)
    {
        $key = trim($key);

        # Validate key
        if (empty($key)) {
            if (static::isDebug()) {
                throw new Runtime("Key \"$key\" not found");
            } else {
                return false;
            }
        }

        # Memory Adaptor
        $this->memory[$key] = $value;

        # Others Adaptors
        foreach ($this->getAdaptors() as $adaptor) {
            $adaptor->put($key, $value);
        }

        return true;
    }

    /**
     * Method for check if .env key exists in Memory Adaptor
     * @param  string $key Env Key
     * @return bool        If exists "true", else "false"
     */
    public function has($key)
    {
        return isset($this->memory[$key]);
    }

    /**
     * Method for get all Memory Adaptor values
     * @return string[]
     */
    public function all()
    {
        return $this->memory;
    }
}
