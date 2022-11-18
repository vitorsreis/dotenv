<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv;

use DotEnv\Exception\Loader;
use DotEnv\Exception\Runtime;
use DotEnv\Exception\Syntax;
use DotEnv\Utils\Adaptors;
use DotEnv\Utils\Debug;
use DotEnv\Utils\IAdaptor;

/**
 * Class DotEnv
 *
 * @package DotEnv
 * @author  Vitor Reis <vitor@d5w.com.br>
 */
class DotEnv
{
    use Adaptors;
    use Debug;

    /**
     * @var string[] Memory Adaptor
     */
    private static $memory;

    /**
     * Method for initialize Memory Adaptor
     * @return void
     */
    public static function bootstrap()
    {
        static::$memory = $_ENV;
    }

    /**
     * Method for get env value by key in Memory Adaptor
     * @param  string $key Env Key
     * @return string|null If success "string", else "null"
     * @throws Runtime
     */
    public static function get($key)
    {
        if (!isset(static::$memory[$key])) {
            if (static::isDebug()) {
                throw new Runtime("Key \"$key\" not found");
            } else {
                return null;
            }
        }

        return static::$memory[$key];
    }

    /**
     * Method for put env key/value in all enabled adaptors
     * @param  string $key   Env Key
     * @param  string $value Env Value
     * @return bool          If success "true", else "false"
     * @throws Runtime
     */
    public static function put($key, $value)
    {
        if (empty($key)) {
            if (static::isDebug()) {
                throw new Runtime("Key \"$key\" not found");
            } else {
                return false;
            }
        }

        # Memory Adaptor
        static::$memory[$key] = $value;

        # Others Adaptors
        foreach (static::$adaptors as $adaptor => $status) {
            if ($status) {
                $adaptor = "\\DotEnv\\Adaptor\\$adaptor";

                /** @var IAdaptor $adaptor */
                $adaptor::put($key, $value);
            }
        }

        return true;
    }

    /**
     * Method for check if .env key exists in Memory Adaptor
     * @param  string $key Env Key
     * @return bool        If exists "true", else "false"
     */
    public static function has($key)
    {
        return isset(static::$memory[$key]);
    }

    /**
     * Method for get all Memory Adaptor values
     * @return string[]
     */
    public static function all()
    {
        return static::$memory;
    }

    /**
     * Method for load .env files
     * @param  string ...$paths .env paths
     * @return string[]|false    If success return "array", else "false"
     * @throws Loader|Runtime|Syntax
     */
    public static function load(...$paths)
    {
        if (!$paths) {
            # Load .env in current file directory
            $paths = [ './.env' ];
        }

        $count = 0;
        $result = [];
        do {
            $path = array_shift($paths);

            if (!is_file($path)) {
                if (self::isDebug()) {
                    throw new Loader("File \"$path\" not found");
                } else {
                    continue;
                }
            }

            if (!is_null($parse = self::parse(file_get_contents($path, FILE_TEXT), realpath($path)))) {
                $result = array_merge($result, $parse);
                $count++;
            }
        } while ($paths);

        if ($count) {
            return $result;
        } elseif (self::isDebug()) {
            throw new Loader("None env variable loaded");
        } else {
            return false;
        }
    }

    /**
     * Method for parse .env content
     * @param  string      $content .env Content
     * @param  string|null $path    .env Path
     * @return string[]|null        If success "string[]", else "null"
     * @throws Runtime|Syntax
     */
    public static function parse($content, $path = null)
    {
        # EMPTY CONTENT
        if (!$content) {
            return [];
        }

        # FIX BREAK LINE
        $content = str_replace([ '\r', "\r", '\n' ], [ "", "", "\n" ], $content);

        # GET LINES
        $lines = explode("\n", $content);

        $lineno = 0;
        $result = [];
        do {
            $lineno++;
            $line = ltrim(array_shift($lines));

            # EMPTY LINE OR COMMENT
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            # INVALIDATE EMPTY DELIMITER
            if (strpos($line, '=') === false) {
                if (static::isDebug()) {
                    throw new Syntax('Value not defined', $path, $lineno);
                } else {
                    continue;
                }
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            # INVALIDATE EMPTY KEY
            if (empty($key)) {
                if (static::isDebug()) {
                    throw new Syntax('Key not defined', $path, $lineno);
                } else {
                    continue;
                }
            }

            # INVALIDATE KEY NOT STARTED WITH [a-zA-Z_]
            if (!preg_match("/[a-zA-Z_]/", $key[0])) {
                throw new Syntax('Key not defined', $path, $lineno);
            }

            # INVALIDATE KEY CHARACTERS NOT [a-zA-Z0-9_]
            if (preg_match("/\W/", $key)) {
                if (static::isDebug()) {
                    throw new Syntax('Key invalid character', $path, $lineno);
                } else {
                    continue;
                }
            }

            $value = ltrim($value);
            if (empty($value)) {
                # EMPTY VALUE
                self::put($key, '');
            } else {
                switch ($value[0]) {
                    # QUOTED VALUE
                    case "'":
                    case '"':
                        $lineno_start = $lineno;
                        $append = false;

                        do {
                            $value .= $append !== false ? "\n$append" : '';

                            if (preg_match(
                                "/($value[0])((?:\\\\$value[0]|[^$value[0]])+)(\\1|$)/",
                                $value,
                                $matches
                            )) {
                                if ($matches[1] === $value[0] && $matches[3] === $value[0]) {
                                    break;
                                }
                            }

                            $matches = null;
                            $append = array_shift($lines);
                            $lineno++;
                        } while (!is_null($append));

                        if ($matches) {
                            $result[$key] = $matches[2];
                            self::put($key, $matches[2]);
                        } elseif (static::isDebug()) {
                            throw new Syntax('Quote not closed', $path, $lineno_start);
                        } else {
                            continue 2;
                        }
                        break;

                    # SIMPLE VALUE
                    default:
                        # INVALIDATE SPACE IN VALUE NOT QUOTED
                        if (stripos($value, " ") !== false) {
                            if (static::isDebug()) {
                                throw new Syntax('Value invalid character', $path, $lineno);
                            } else {
                                continue 2;
                            }
                        }

                        $result[$key] = $value;
                        self::put($key, $value);
                        break;
                }
            }
        } while ($lines);

        return $result;
    }
}
