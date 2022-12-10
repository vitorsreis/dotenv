# .env for PHP
Simple library for load .env files in php runtime

## Install
```shell
composer require vitorsreis/dotenv
```

## Usage

###### Example 1: for load with construct
```php
$dotenv = new \DotEnv\DotEnv(".env", ...);
```

###### Example 2: for load .env in current file directory
```php
($dotenv = new \DotEnv\DotEnv())->load();
```

###### Example 3: for load specific .env paths
```php
($dotenv = new \DotEnv\DotEnv())->load("/home/var/www/.env.production", ...);
```

###### Example 4: for load with string
```php
($dotenv = new \DotEnv\DotEnv())->parse(" USER=vitor \n NAME='Vitor Reis' \n TYPE=ADMIN ");
```

###### Example 5: for load with static bootstrap
```
[OPTIONAL] 'debug'   => bool,
[OPTIONAL] 'adaptor' => IAdaptor|IAdaptor[],
[OPTIONAL] 'scheme'  => array[ ...'ENV_KEY' => ...Convert/Rules ],
[OPTIONAL] 'load'    => string|string[],
[OPTIONAL] 'parse'   => string|string[]
```

```php
$dotenv = \DotEnv\DotEnv::bootstrap([
    'debug'   => true,
    'adaptor' => [
        new \DotEnv\Adaptor\ApacheGetEnv(),
        new \DotEnv\Adaptor\EnvSuperGlobal()
    ],
    'scheme'  => [
        'IS_SUDO'  => \DotEnv\Rule::IS_REQUIRED, // REQUIRED RULE
        'IS_ADMIN' => \DotEnv\Converter::TO_BOOL_OR_NULL, // CONVERT TO BOOL OR NULL
        'FACTOR'   => [ // MULTI RULES AND CONVERTER
            \DotEnv\Rule::IS_INT,
            \DotEnv\Rule::IS_MIN_VALUE => 10,
            \DotEnv\Rule::IS_MAX_VALUE => 200,
            \DotEnv\Converter::TO_INT
        ],
        'NAME'     => [ // MULTI RULES WITH CUSTOM CONVERTER
            \DotEnv\Rule::IS_RANGE_LENGTH => [ 10, 100 ], // or [ 'min' => 10, 'max' => 100 ]
            \DotEnv\Rule::IS_CUSTOM => function ($value) {
                return $value === 'Vitor';
            },
            \DotEnv\Converter::TO_CUSTOM => function ($value) => {
                return intval($value) * 10;
            }
        ]
    ],
    'load'   => __DIR__ . ".env" // or [ "./.env", "./.env.global" ],
    'parse'  => [
        'CUSTOM_ENV_VARIABLE_1=VALUE_1',
        'CUSTOM_ENV_VARIABLE_2' => 'VALUE_2'
    ]
]);
```

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
```php
$dotenv = new \DotEnv\DotEnv();
$dotenv->adaptor(new \DotEnv\Adaptor\ApacheGetEnv());
$dotenv->adaptor(new \DotEnv\Adaptor\EnvSuperGlobal(), 'adaptor-global-env');
$dotenv->load();
```

Adaptor unload example:
```php
$dotenv = new \DotEnv\DotEnv();
$dotenv->removeAdaptor(\DotEnv\Adaptor\ApacheGetEnv::class);
$dotenv->removeAdaptor('adaptor-global-env');
```

## Variables convert support

| Support | Constant                             | Function         | Description                                                     |
|:-------:|:-------------------------------------|:-----------------|:----------------------------------------------------------------|
|    ✅    | \DotEnv\Converter::TO_CUSTOM         | toCustom()       | Custom callable convert : _function ($value) { return mixed; }_ |
|    ✅    | \DotEnv\Converter::TO_STRING         | toString()       | Convert variable as string                                      |
|    ✅    | \DotEnv\Converter::TO_STRING_OR_NULL | toStringOrNull() | Convert variable as string or null                              |
|    ✅    | \DotEnv\Converter::TO_BOOL           | toBool()         | Convert variable as bool                                        |
|    ✅    | \DotEnv\Converter::TO_BOOL_OR_NULL   | toBoolOrNull()   | Convert variable as bool or null                                |
|    ✅    | \DotEnv\Converter::TO_INT            | toInt()          | Convert variable as int                                         |
|    ✅    | \DotEnv\Converter::TO_INT_OR_NULL    | toIntOrNull()    | Convert variable as int or null                                 |
|    ✅    | \DotEnv\Converter::TO_FLOAT          | toFloat()        | Convert variable as float                                       |
|    ✅    | \DotEnv\Converter::TO_FLOAT_OR_NULL  | toFloatOrNull()  | Convert variable as float or null                               |

Converter usage example:
```php
$dotenv = new \DotEnv\DotEnv();
$dotenv->convert('IS_ADMIN', 'IS_SUDO', ...)->toBool();
$dotenv->convert('FACTOR', ...)->toCustom(function ($value) {
    return intval($value) * 100;
});
$dotenv->load();
```

## Variables rule support

| Support | Constant                                                                                                             | Function                  | Description                            |
|:-------:|:---------------------------------------------------------------------------------------------------------------------|:--------------------------|:---------------------------------------|
|    ✅    | \DotEnv\Rule::IS_CUSTOM => _?callable : function ($value) { return bool; } or null_                                  | isCustom(?callable)       | Custom callable rule                   |
|    ✅    | \DotEnv\Rule::IS_REGEX => _?string_                                                                                  | isRegex(?string)          | Regex rule                             |
|    ✅    | \DotEnv\Rule::IS_REQUIRED =>  _bool, if implicit = true_                                                             | isRequired(bool=true)     | Required env key                       |
|    ✅    | \DotEnv\Rule::IS_NOT_ALLOW =>  _bool, if implicit = true_                                                            | isNotAllow(bool=true)     | Not allow env key                      |
|    ✅    | \DotEnv\Rule::IS_BOOL =>  _bool, if implicit = true_                                                                 | isBool(bool=true)         | Check if value is boolean              |
|    ✅    | \DotEnv\Rule::IS_INT =>  _bool, if implicit = true_                                                                  | isInt(bool=true)          | Check if value is integer              |
|    ✅    | \DotEnv\Rule::IS_FLOAT =>  _bool, if implicit = true_                                                                | isFloat(bool=true)        | Check if value is float                |
|    ✅    | \DotEnv\Rule::IS_MIN_VALUE =>  _?numeric_                                                                            | isMinValue(?numeric)      | Check if value numeric is min          |
|    ✅    | \DotEnv\Rule::IS_MAX_VALUE =>  _?numeric_                                                                            | isMaxValue(?numeric)      | Check if value numeric is max          |
|    ✅    | \DotEnv\Rule::IS_RANGE_VALUE => \[ _?numeric_, _?numeric_ ] or \[ 'min' => _?numeric_, 'max' => _?numeric_ ] or null | isRangeValue(?int, ?int)  | Check if value numeric is in range     |
|    ✅    | \DotEnv\Rule::IS_STRING =>  _bool, if implicit = true_                                                               | isString(bool=true)       | Check if value is string               |
|    ✅    | \DotEnv\Rule::IS_MIN_LENGTH =>  _?int_                                                                               | isMinLength(?int)         | heck if value string has min length    |
|    ✅    | \DotEnv\Rule::IS_MAX_LENGTH =>  _?int_                                                                               | isMaxLength(?int)         | Check if value string has max length   |
|    ✅    | \DotEnv\Rule::IS_RANGE_LENGTH  => \[ _?int_, _?int_ ] or \[ 'min' => _?int_, 'max' => _?int_ ] or null               | isRangeLength(?int, ?int) | Check if value string has range length |
|    ✅    | \DotEnv\Rule::IS_EMPTY =>  _bool, if implicit = true_                                                                | isEmpty(bool=true)        | Check if value is empty                |
|    ✅    | \DotEnv\Rule::IS_NOT_EMPTY =>  _bool, if implicit = true_                                                            | isNotEmpty(bool=true)     | Check if value is not empty            |
|    ✅    | \DotEnv\Rule::IS_NULL =>  _bool, if implicit = true_                                                                 | isNull(bool=true)         | Check if value is null                 |
|    ✅    | \DotEnv\Rule::IS_NOT_NULL =>  _bool, if implicit = true_                                                             | isNotNull(bool=true)      | Check if value is not null             |
|    ✅    | \DotEnv\Rule::IS_EMAIL =>  _bool, if implicit = true_                                                                | isEmail(bool=true)        | Check if value is e-mail               |
|    ✅    | \DotEnv\Rule::IS_IP =>  _bool, if implicit = true_                                                                   | isIp(bool=true)           | Check if value is IP Address           |
|    ✅    | \DotEnv\Rule::IS_IPV4 =>  _bool, if implicit = true_                                                                 | isIpv4(bool=true)         | Check if value is IPv4 Address         |
|    ✅    | \DotEnv\Rule::IS_IPV6 =>  _bool, if implicit = true_                                                                 | isIpv6(bool=true)         | Check if value is IPv6 Address         |
|    ✅    | \DotEnv\Rule::IS_MAC =>  _bool, if implicit = true_                                                                  | isMac(bool=true)          | Check if value is MAC Address          |
|    ✅    | \DotEnv\Rule::IS_URL =>  _bool, if implicit = true_                                                                  | isUrl(bool=true)          | Check if value is URL String           |

Validation usage example:
```php
$dotenv = new \DotEnv\DotEnv();

$dotenv->rule('IS_ADMIN', 'IS_SUDO', ...)->isBool();

$dotenv->rule('FACTOR', ...)
    ->isInt()
    ->isRangeValue(10, 200);

$dotenv->rule('NAME', ...)->isCustom(function ($value) {
    return $value === 'Vitor';
});

$dotenv->load();
```

## Convert / rules by scheme example:
```php
$dotenv = new \DotEnv\DotEnv();

$dotenv->scheme([
    'IS_SUDO' => \DotEnv\Converter::TO_BOOL,

    'IS_ADMIN' => \DotEnv\Converter::TO_BOOL_OR_NULL,

    'FACTOR' => [
        \DotEnv\Converter::TO_CUSTOM => function ($value) => {
            return intval($value) * 10;
        },
        \DotEnv\Rule::IS_INT,
        \DotEnv\Rule::IS_MIN_VALUE => 10,
        \DotEnv\Rule::IS_MAX_VALUE => 200,
    ]

    'NAME' => [
        \DotEnv\Rule::IS_CUSTOM => function ($value) {
            return $value === 'Vitor';
        },
        \DotEnv\Rule::IS_RANGE_LENGTH => [ 10, 100 ] // or [ 'min' => 10, 'max' => 100 ]
    ]
]);
```

## Debug mode

Enable debug mode to receive all alerts (default: disabled)
```php
\DotEnv\DotEnv::enableDebug();

$dotenv = new \DotEnv\DotEnv();
```