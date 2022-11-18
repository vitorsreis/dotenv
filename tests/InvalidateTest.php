<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv\Tests;

use DotEnv\DotEnv;
use DotEnv\Exception\Loader;
use DotEnv\Exception\Syntax;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidateTest
 *
 * @package DotEnv\Tests
 * @author  Vitor Reis <vitor@d5w.com.br>
 * @testdox Invalidate values test
 */
class InvalidateTest extends TestCase
{
    public function __construct($name = null, $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        DotEnv::enableDebug();
    }

    /**
     * @testdox Invalidate: File not found
     */
    public function testFileNotFound()
    {
        $this->expectException(Loader::class);
        DotEnv::load(uniqid());
    }

    /**
     * @testdox Invalidate: Key with empty value
     */
    public function testKeyWithEmptyValue()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('KEY');
    }

    /**
     * @testdox Invalidate: Empty key with value
     */
    public function testEmptyKeyWithValue()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('=VALUE');
    }

    /**
     * @testdox Invalidate: Empty key and value
     */
    public function testEmptyKeyAndValue()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('=');
    }

    /**
     * @testdox Invalidate: Key with space
     */
    public function testKeyWithSpace()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('KEY KEY=VALUE');
    }

    /**
     * @testdox Invalidate: Key with hyphen
     */
    public function testKeyWithHyphen()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('K-EY=VALUE');
    }

    /**
     * @testdox Invalidate: Key with invalid character [^a-zA-Z0-9_]
     */
    public function testKeyWithInvalidCharacter()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('AE!Y=VALUE');
    }

    /**
     * @testdox Invalidate: Key not started with "A-Z" or "_"
     */
    public function testKeyStart()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('1EY=VALUE');
    }

    /**
     * @testdox Invalidate: Value with space not quoted
     */
    public function testValueWithSpaceNotQuoted()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('KEY=VAL UE');
    }

    /**
     * @testdox Invalidate: Start quote not closed
     */
    public function testStartQuoteNotClosed()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse("1EY='VALUE\'");
    }

    /**
     * @testdox Invalidate: Start double quote not closed
     */
    public function testStartDoubleQuoteNotClosed()
    {
        $this->expectException(Syntax::class);
        DotEnv::parse('1EY="VALUE\"');
    }
}
