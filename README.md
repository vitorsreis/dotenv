# .env for PHP
Simple library for load .env files in php runtime with static class

## Install
    composer require vitorsreis/dotenv

## Usage

###### Example 1: for load .env in current file directory
    \DotEnv::load();

###### Example 2: for load specific .env path
    \DotEnv::load("/home/var/www/.env.production");

###### Example 3: for load multi .env
    \DotEnv::load(".env.global", ".env.production", ...);

## Syntax supports
| Support | Name                             | Example                                         |
|:-------:|----------------------------------|-------------------------------------------------|
|    ✅    | Trim empty line                  | (*Ignore*)                                      |
|    ✅    | Comment                          | **#** COMMENT (*Ignore*)                        |
|    ✅    | Text                             | KEY=VALUE                                       |
|    ✅    | Quote (support multiline)        | KEY='VALUE VALUE VALUE' COMMENT COMMENT COMMENT |
|    ✅    | Double quote (support multiline) | KEY="VALUE VALUE VALUE" COMMENT COMMENT COMMENT |

## Engine supports for get value by key
| Support | Key               | Engine                  | Default status | Example code                       |
|:-------:|-------------------|-------------------------|----------------|------------------------------------|
|    ✅    | -                 | Array return in load    | *Enabled       | $array = \DotEnv\DotEnv::load(...) |
|    ✅    | -                 | Alloc in memory         | *Enabled       | \DotEnv\DotEnv::get('KEY')         |
|    ✅    | Constant          | Create Constant         | Disabled       | KEY *or* constant('KEY')           |
|    ✅    | ApacheGetEnv      | Function apache_getenv  | Disabled       | apache_getenv('KEY')               |
|    ✅    | EnvSuperGlobal    | $_ENV (Super Global)    | Enabled        | $_ENV\['KEY']                      |
|    ✅    | GetEnv            | Function getenv         | Enabled        | getenv('KEY')                      |
|    ✅    | ServerSuperGlobal | $_SERVER (Super Global) | Disabled       | $_SERVER\['KEY']                   |