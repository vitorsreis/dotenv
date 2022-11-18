<?php
/**
 * This file is part of PHP DotEnv
 *
 * @author    Vitor Reis <vitor@d5w.com.br>
 * @copyright 2022 D5W Group. All rights reserved.
 */

namespace DotEnv\Tests;

use DotEnv\DotEnv;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidateTest
 *
 * @package DotEnv\Tests
 * @author  Vitor Reis <vitor@d5w.com.br>
 * @testdox Validate values test
 */
class ValidateTest extends TestCase
{
    public function __construct($name = null, $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        DotEnv::enableDebug();
    }

    /**
     * @testdox Validate: Simple
     */
    public function testSimple()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            DotEnv::parse("KEY=VALUE")
        );
    }

    /**
     * @testdox Validate: With quote
     */
    public function testWithQuote()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            DotEnv::parse("KEY='VALUE'")
        );
    }

    /**
     * @testdox Validate: With quote and comment
     */
    public function testWithQuoteAndComment()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE VALUE' ],
            DotEnv::parse("KEY='VALUE VALUE' COMMENT COMMENT")
        );
    }

    /**
     * @testdox Validate: With double quotes
     */
    public function testWithDoubleQuotes()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            DotEnv::parse('KEY="VALUE"')
        );
    }

    /**
     * @testdox Validate: With double quotes and comment
     */
    public function testWithDoubleQuotesAndComment()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE VALUE' ],
            DotEnv::parse('KEY="VALUE VALUE" COMMENT COMMENT')
        );
    }

    /**
     * @testdox Validate: Started with A-Z
     */
    public function testStartedWithAZ()
    {
        $this->assertEquals(
            [ 'K11' => 'VALUE' ],
            DotEnv::parse('K11=VALUE')
        );
    }

    /**
     * @testdox Validate: Started with underline
     */
    public function testStartedWithUnderline()
    {
        $this->assertEquals(
            [ '_KEY' => 'VALUE' ],
            DotEnv::parse('_KEY=VALUE')
        );
    }

    /**
     * @testdox Validate: LTrim key
     */
    public function testLTrimKey()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            DotEnv::parse('   KEY=VALUE')
        );
    }

    /**
     * @testdox Validate: RTrim key
     */
    public function testRTrimKey()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            DotEnv::parse('KEY   =VALUE')
        );
    }

    /**
     * @testdox Validate: LTrim value
     */
    public function testLTrimValue()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            DotEnv::parse('KEY=   VALUE')
        );
    }

    /**
     * @testdox Validate: RTrim value
     */
    public function testRTrimValue()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            DotEnv::parse('KEY=VALUE   ')
        );
    }

    /**
     * @testdox Validate: Quote with escape quote
     */
    public function testQuoteWithEscapeQuote()
    {
        $this->assertEquals(
            [ 'KEY' => "VALUE \'\" VALUE" ],
            DotEnv::parse("KEY='VALUE \'\" VALUE' COMMENT COMMENT")
        );
    }

    /**
     * @testdox Validate: Double quote with escape quote
     */
    public function testDoubleQuoteWithEscapeQuote()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE \'\" VALUE' ],
            DotEnv::parse('KEY="VALUE \'\" VALUE" COMMENT COMMENT')
        );
    }

    /**
     * @testdox Validate: Quote multiline
     */
    public function testQuoteMultiline()
    {
        $this->assertEquals(
            [ 'KEY' => "VALUE\nVALUE\nVALUE" ],
            DotEnv::parse("KEY='VALUE\r\nVALUE\r\nVALUE' COMMENT COMMENT")
        );
    }

    /**
     * @testdox Validate: Double quote multiline
     */
    public function testDoubleQuoteMultiline()
    {
        $this->assertEquals(
            [ 'KEY' => "VALUE\nVALUE\nVALUE" ],
            DotEnv::parse('KEY="VALUE\r\nVALUE\r\nVALUE" COMMENT COMMENT')
        );
    }

    /**
     * @testdox Validate: Empty Line
     */
    public function testEmptyLine()
    {
        $this->assertEquals(
            [],
            DotEnv::parse("   # COMMENT   ")
        );
    }

    /**
     * @testdox Validate: Comment
     */
    public function testComment()
    {
        $this->assertEquals(
            [],
            DotEnv::parse("   # COMMENT   ")
        );
    }
}
