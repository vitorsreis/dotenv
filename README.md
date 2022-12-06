# .env for PHP
Simple library for load .env files in php runtime

## Install
    composer require vitorsreis/dotenv

## Usage

###### Example 1: for load with construct
    $dotenv = new \DotEnv\DotEnv(".env", ...);

###### Example 2: for load .env in current file directory
    ($dotenv = new \DotEnv\DotEnv())->load();

###### Example 3: for load specific .env paths
    ($dotenv = new \DotEnv\DotEnv())->load("/home/var/www/.env.production", ...);

###### Example 4: for load with string
    ($dotenv = new \DotEnv\DotEnv())->parse(" USER=vitor \n NAME='Vitor Reis' \n TYPE=ADMIN ");

## Syntax supports
| Support | Name                             | Example                                         |
|:-------:|----------------------------------|-------------------------------------------------|
|    ✅    | Trim empty line                  | (*Ignore*)                                      |
|    ✅    | Comment                          | **#** COMMENT (*Ignore*)                        |
|    ✅    | Text                             | KEY=VALUE                                       |
|    ✅    | Quote (support multiline)        | KEY='VALUE VALUE VALUE' COMMENT COMMENT COMMENT |
|    ✅    | Double quote (support multiline) | KEY="VALUE VALUE VALUE" COMMENT COMMENT COMMENT |

## Adaptors support

| Support | Key               | Engine                  | Default status | Example adaptor get value   |
|:-------:|-------------------|-------------------------|----------------|-----------------------------|
|    ✅    | -                 | Array return in load    | *Enabled       | $array = $dotenv->load(...) |
|    ✅    | -                 | Alloc in memory         | *Enabled       | $dotenv->get('KEY')         |
|    ✅    | Constant          | Create Constant         | Disabled       | KEY *or* constant('KEY')    |
|    ✅    | ApacheGetEnv      | Function apache_getenv  | Disabled       | apache_getenv('KEY')        |
|    ✅    | EnvSuperGlobal    | $_ENV (Super Global)    | Disabled       | $_ENV\['KEY']               |
|    ✅    | GetEnv            | Function getenv         | Disabled       | getenv('KEY')               |
|    ✅    | ServerSuperGlobal | $_SERVER (Super Global) | Disabled       | $_SERVER\['KEY']            |

Adaptor load example:

    $dotenv = new \DotEnv\DotEnv();

    $dotenv->adaptor(new \DotEnv\Adaptor\ApacheGetEnv());

    $dotenv->adaptor(new \DotEnv\Adaptor\EnvSuperGlobal());

    $dotenv->load();

Adaptor unload example:

    $dotenv = new \DotEnv\DotEnv();

    $dotenv->removeAdaptor('ApacheGetEnv');

## Variables convert support

| Support | Key            | Description                                                     |
|:-------:|----------------|-----------------------------------------------------------------|
|    ✅    | toCustom       | Custom callable convert : _function ($value) { return mixed; }_ |
|    ✅    | toString       | Convert variable as string                                      |
|    ✅    | toStringOrNull | Convert variable as string or null                              |
|    ✅    | toBool         | Convert variable as bool                                        |
|    ✅    | toBoolOrNull   | Convert variable as bool or null                                |
|    ✅    | toInt          | Convert variable as int                                         |
|    ✅    | toIntOrNull    | Convert variable as int or null                                 |
|    ✅    | toFloat        | Convert variable as float                                       |
|    ✅    | toFloatOrNull  | Convert variable as float or null                               |

Converter usage example:

    $dotenv = new \DotEnv\DotEnv();

    $dotenv->convert('IS_ADMIN', 'IS_SUDO', ...)->toBool();

    $dotenv->convert('FACTOR', ...)->toCustom(function ($value) {
        return intval($value) * 100;
    });

    $dotenv->load();

## Variables rule support

| Support | Key           | Description                                                  |
|:-------:|---------------|--------------------------------------------------------------|
|    ✅    | isCustom      | Custom callable rule  : _function ($value) { return bool; }_ |
|    ✅    | isRegex       | Regex rule                                                   |
|    ✅    | isRequired    | Required env key                                             |
|    ✅    | isNotAllow    | Not allow env key                                            |
|    ✅    | isBool        | Check if value is boolean                                    |
|    ✅    | isInt         | Check if value is integer                                    |
|    ✅    | isFloat       | Check if value is float                                      |
|    ✅    | isMinValue    | Check if value numeric is min                                |
|    ✅    | isMaxValue    | Check if value numeric is max                                |
|    ✅    | isRangeValue  | Check if value numeric is in range                           |
|    ✅    | isString      | Check if value is string                                     |
|    ✅    | isMinLength   | Check if value string has min length                         |
|    ✅    | isMaxLength   | Check if value string has max length                         |
|    ✅    | isRangeLength | Check if value string has range length                       |
|    ✅    | isEmpty       | Check if value is empty                                      |
|    ✅    | isNotEmpty    | Check if value is not empty                                  |
|    ✅    | isNull        | Check if value is null                                       |
|    ✅    | isNotNull     | Check if value is not null                                   |
|    ✅    | isEmail       | Check if value is e-mail                                     |
|    ✅    | isIp          | Check if value is IP Address                                 |
|    ✅    | isIpv4        | Check if value is IPv4 Address                               |
|    ✅    | isIpv6        | Check if value is IPv6 Address                               |
|    ✅    | isMac         | Check if value is MAC Address                                |
|    ✅    | isUrl         | Check if value is URL String                                 |

Validation usage example:

    $dotenv = new \DotEnv\DotEnv();

    $dotenv->rule('IS_ADMIN', 'IS_SUDO', ...)->isBool();

    $dotenv->rule('FACTOR', ...)
        ->isInt()
        ->isRangeValue(10, 200);

    $dotenv->rule('NAME', ...)->isCustom(function ($value) {
        return $value === 'Vitor';
    });

    $dotenv->load();

## Debug mode

Enable debug mode to receive all alerts (default: disabled)

    \DotEnv\DotEnv::enableDebug();

    $dotenv = new \DotEnv\DotEnv();