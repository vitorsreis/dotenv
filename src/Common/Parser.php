<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Common;

use DotEnv\Converter;
use DotEnv\DotEnv;
use DotEnv\Exception\Loader;
use DotEnv\Exception\Runtime;
use DotEnv\Exception\Syntax;
use DotEnv\Rule;
use DotEnv\Utils\IAdaptor;

/**
 * Trait Parser
 * @package DotEnv\Common
 */
trait Parser
{
    /**
     * Method for load .env files
     * @param  string          ...$paths .env paths
     * @return string[]|false            If success return "array", else "false"
     * @throws Loader|Runtime|Syntax
     */
    public function load(...$paths)
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
                if (static::isDebug()) {
                    throw new Loader("File \"$path\" not found");
                } else {
                    continue;
                }
            }

            if (!is_null($parse = $this->parse(file_get_contents($path), realpath($path)))) {
                $result = array_merge($result, $parse);
                $count++;
            }
        } while ($paths);

        if ($count) {
            return $result;
        } elseif (static::isDebug()) {
            throw new Loader("None env variable loaded");
        } else {
            return false;
        }
    }

    /**
     * Method for parse .env content
     * @param array|string   $content .env Content
     * @param string|null    $path    .env Path
     * @return string[]|null          If success "string[]", else "null"
     * @throws Loader|Runtime|Syntax
     */
    public function parse($content, $path = null)
    {
        if (is_array($content)) {
            $content = implode(PHP_EOL, $content);
        }

        if (!$content) {
            # EMPTY CONTENT
            return [];
        }

        # FIX BREAK LINE
        $content = str_replace([ '\r', "\r", '\n' ], [ "", "", "\n" ], $content);

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
                $value = '';
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
                            $value = $matches[2];
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
                        break;
                }
            }

            # CONVERT VALUE
            $this->converter($key, $value);

            # INVALIDATE VALUE
            if ($rule = $this->invalidate($key, $value)) {
                throw new Loader("Value \"$key\" does not meet rule \"$rule\"!");
            }

            # SAVE VALUE
            $result[$key] = $value;
            $this->put($key, $value);
        } while ($lines);

        # CHECK REQUIRE LIST
        if ($requiredKey = $this->invalidateRequiredList()) {
            throw new Loader("Env key \"$requiredKey\" required, is not defined!");
        }

        return $result;
    }

    /**
     * Method for set scheme convert/rules
     * @param array $scheme Scheme convert/rules
     * @return $this
     * @throws Syntax|Runtime
     */
    public function scheme($scheme)
    {
        foreach ($scheme as $key => $scope) {
            $key = trim($key);

            if (is_string($scope)) {
                $scope = [ $scope ];
            }

            foreach ($scope as $scopeKey => $scopeValue) {
                if (is_numeric($scopeKey)) {
                    if (in_array($scopeValue, Rule::__constants()) || in_array($scopeValue, Converter::__constants())) {
                        $scopeKey = $scopeValue;
                        $scopeValue = true;
                    }
                }

                if (substr($scopeKey, 0, 4) === 'Rule') {
                    $this->rule($key)->setRule($scopeKey, $scopeValue);
                } elseif (substr($scopeKey, 0, 7) === 'Convert') {
                    $this->convert($key)->setConverter($scopeKey, $scopeValue);
                } else {
                    throw new Syntax("Invalid scheme type \"$scopeKey\" in \"$key\"");
                }
            }
        }
        return $this;
    }

    /**
     * Method for clear bootstrap DotEnv class
     * @param array{
     *     debug: bool,
     *     adaptor:IAdaptor|IAdaptor[],
     *     scheme:array,
     *     load:string|string[],
     *     parse:string|string[]
     * } $setting Construct setting<pre>
     * debug    Boolean, Optional
     * adaptor  IAdaptor[], Optional
     * scheme   [ ?...'ENV_KEY' => [ ?DotEnv\Converter::, ?...DotEnv\Rule::, ] ], Optional
     * load     string|string[], Optional
     * parse    string|string[], Optional</pre>
     * @return $this
     * @throws Loader|Runtime|Syntax
     */
    public static function bootstrap($setting)
    {
        $dotenv = new DotEnv();

        if (isset($setting['debug'])) {
            if ($setting['debug']) {
                $dotenv::enableDebug();
            } else {
                $dotenv::disableDebug();
            }
        }

        if (isset($setting['adaptor'])) {
            if (is_string($setting['adaptor'])) {
                $setting['adaptor'] = [ $setting['adaptor'] ];
            }

            if (is_array($setting['adaptor'])) {
                foreach ($setting['adaptor'] as $key => $adaptor) {
                    $dotenv->adaptor($adaptor, is_string($key) ? $key : null);
                }
            }
        }

        if (isset($setting['scheme'])) {
            if (is_array($setting['scheme'])) {
                $dotenv->scheme($setting['scheme']);
            }
        }

        if (isset($setting['load'])) {
            if (is_string($setting['load'])) {
                $setting['load'] = [ $setting['load'] ];
            }

            if (is_array($setting['load'])) {
                $dotenv->load(...$setting['load']);
            }
        }

        if (isset($setting['parse'])) {
            if (is_string($setting['parse']) || is_array($setting['parse'])) {
                $dotenv->parse($setting['parse']);
            }
        }

        return $dotenv;
    }
}
