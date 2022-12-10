<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Test;

use DotEnv\DotEnv;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidateTest
 * @package DotEnv\Tests
 * @testdox Validate values test
 */
class ValidateTest extends TestCase
{
    private $dotenv;
    public function __construct($name = null, $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->dotenv = new DotEnv();
        $this->dotenv->enableDebug();
    }

    /**
     * @testdox Validate: Simple
     */
    public function testSimple()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            $this->dotenv->parse("KEY=VALUE")
        );
    }

    /**
     * @testdox Validate: With quote
     */
    public function testWithQuote()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            $this->dotenv->parse("KEY='VALUE'")
        );
    }

    /**
     * @testdox Validate: With quote and comment
     */
    public function testWithQuoteAndComment()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE VALUE' ],
            $this->dotenv->parse("KEY='VALUE VALUE' COMMENT COMMENT")
        );
    }

    /**
     * @testdox Validate: With double quotes
     */
    public function testWithDoubleQuotes()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            $this->dotenv->parse('KEY="VALUE"')
        );
    }

    /**
     * @testdox Validate: With double quotes and comment
     */
    public function testWithDoubleQuotesAndComment()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE VALUE' ],
            $this->dotenv->parse('KEY="VALUE VALUE" COMMENT COMMENT')
        );
    }

    /**
     * @testdox Validate: Started with A-Z
     */
    public function testStartedWithAZ()
    {
        $this->assertEquals(
            [ 'K11' => 'VALUE' ],
            $this->dotenv->parse('K11=VALUE')
        );
    }

    /**
     * @testdox Validate: Started with underline
     */
    public function testStartedWithUnderline()
    {
        $this->assertEquals(
            [ '_KEY' => 'VALUE' ],
            $this->dotenv->parse('_KEY=VALUE')
        );
    }

    /**
     * @testdox Validate: LTrim key
     */
    public function testLTrimKey()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            $this->dotenv->parse('   KEY=VALUE')
        );
    }

    /**
     * @testdox Validate: RTrim key
     */
    public function testRTrimKey()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            $this->dotenv->parse('KEY   =VALUE')
        );
    }

    /**
     * @testdox Validate: LTrim value
     */
    public function testLTrimValue()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            $this->dotenv->parse('KEY=   VALUE')
        );
    }

    /**
     * @testdox Validate: RTrim value
     */
    public function testRTrimValue()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            $this->dotenv->parse('KEY=VALUE   ')
        );
    }

    /**
     * @testdox Validate: Quote with escape quote
     */
    public function testQuoteWithEscapeQuote()
    {
        $this->assertEquals(
            [ 'KEY' => "VALUE \'\" VALUE" ],
            $this->dotenv->parse("KEY='VALUE \'\" VALUE' COMMENT COMMENT")
        );
    }

    /**
     * @testdox Validate: Double quote with escape quote
     */
    public function testDoubleQuoteWithEscapeQuote()
    {
        $this->assertEquals(
            [ 'KEY' => 'VALUE \'\" VALUE' ],
            $this->dotenv->parse('KEY="VALUE \'\" VALUE" COMMENT COMMENT')
        );
    }

    /**
     * @testdox Validate: Quote multiline
     */
    public function testQuoteMultiline()
    {
        $this->assertEquals(
            [ 'KEY' => "VALUE\nVALUE\nVALUE" ],
            $this->dotenv->parse("KEY='VALUE\r\nVALUE\r\nVALUE' COMMENT COMMENT")
        );
    }

    /**
     * @testdox Validate: Double quote multiline
     */
    public function testDoubleQuoteMultiline()
    {
        $this->assertEquals(
            [ 'KEY' => "VALUE\nVALUE\nVALUE" ],
            $this->dotenv->parse('KEY="VALUE\r\nVALUE\r\nVALUE" COMMENT COMMENT')
        );
    }

    /**
     * @testdox Validate: Empty Line
     */
    public function testEmptyLine()
    {
        $this->assertEquals(
            [],
            $this->dotenv->parse("   # COMMENT   ")
        );
    }

    /**
     * @testdox Validate: Comment
     */
    public function testComment()
    {
        $this->assertEquals(
            [],
            $this->dotenv->parse("   # COMMENT   ")
        );
    }

    /**
     * @testdox Validate: Convert To String
     */
    public function testConvertString()
    {
        $this->dotenv->convert('KEY')
            ->toString();

        $this->assertEquals(
            [ 'KEY' => 'VALUE' ],
            $this->dotenv->parse('KEY=VALUE')
        );

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Convert To Bool
     */
    public function testConvertBool()
    {
        $this->dotenv->convert('K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8')
            ->toBool();

        $this->assertEquals(
            [
                'K1' => true,
                'K2' => false,
                'K3' => true,
                'K4' => false,
                'K5' => true,
                'K6' => false,
                'K7' => true,
                'K8' => false
            ],
            $this->dotenv->parse("K1=yes \n K2=no \n K3=on \n K4=off \n K5=true \n K6=false \n K7=1 \n K8=0")
        );

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Convert To Int
     */
    public function testConvertInt()
    {
        $this->dotenv->convert('KEY')
            ->toInt();

        $this->assertEquals(
            [ 'KEY' => 100 ],
            $this->dotenv->parse('KEY=100')
        );

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Convert To Float
     */
    public function testConvertFloat()
    {
        $this->dotenv->convert('KEY')
            ->toFloat();

        $this->assertEquals(
            [ 'KEY' => 1.01 ],
            $this->dotenv->parse('KEY=1.01')
        );

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Convert To Custom
     */
    public function testConvertCustom()
    {
        $this->dotenv->convert('KEY')
            ->toCustom(function ($value) {
                return intval($value) * 100;
            });

        $this->assertEquals(
            [ 'KEY' => 1000 ],
            $this->dotenv->parse("KEY=10")
        );

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Rule Is Regex
     */
    public function testRuleRegex()
    {
        $this->dotenv->rule('KEY')
            ->isRegex('/[\d]/');

        $this->dotenv->parse("KEY=100");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Required
     */
    public function testRuleRequired()
    {
        $this->dotenv->rule('KEY')
            ->isRequired();

        $this->dotenv->parse("KEY=100");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Bool
     */
    public function testRuleBool()
    {
        $this->dotenv->rule('K1', 'K2', 'K3')
            ->isBool();

        $this->dotenv->parse("K1=1 \n K2=yes \n K3=true");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Int
     */
    public function testRuleInt()
    {
        $this->dotenv->rule('KEY')
            ->isInt();

        $this->dotenv->parse("KEY=100");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Float
     */
    public function testRuleFloat()
    {
        $this->dotenv->rule('KEY')
            ->isFloat();

        $this->dotenv->parse("KEY=1.00");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Min Value
     */
    public function testRuleMinValue()
    {
        $this->dotenv->rule('KEY')
            ->isMinValue(10);

        $this->dotenv->parse("KEY=99");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Max Value
     */
    public function testRuleMaxValue()
    {
        $this->dotenv->rule('KEY')
            ->isMaxValue(10);

        $this->dotenv->parse("KEY=1");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Range Value
     */
    public function testRuleRangeValue()
    {
        $this->dotenv->rule('KEY')
            ->isRangeValue(10, 12);

        $this->dotenv->parse("KEY=11");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is String
     */
    public function testRuleString()
    {
        $this->dotenv->rule('KEY')
            ->isString();

        $this->dotenv->parse("KEY=AAA");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Min Length
     */
    public function testRuleMinLength()
    {
        $this->dotenv->rule('KEY')
            ->isMinLength(3);

        $this->dotenv->parse("KEY=AAA");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Max Length
     */
    public function testRuleMaxLength()
    {
        $this->dotenv->rule('KEY')
            ->isMaxLength(3);

        $this->dotenv->parse("KEY=AAA");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Range Length
     */
    public function testRuleRangeLength()
    {
        $this->dotenv->rule('KEY')
            ->isRangeLength(2, 4);

        $this->dotenv->parse("KEY=AAA");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Empty
     */
    public function testRuleEmpty()
    {
        $this->dotenv->rule('KEY')
            ->isEmpty();

        $this->dotenv->parse("KEY=");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Not Empty
     */
    public function testRuleNotEmpty()
    {
        $this->dotenv->rule('KEY')
            ->isNotEmpty();

        $this->dotenv->parse("KEY=A");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Null
     */
    public function testRuleNull()
    {
        $this->dotenv->convert('KEY')
            ->toBoolOrNull();

        $this->dotenv->rule('KEY')
            ->isNull();

        $this->dotenv->parse("KEY=A");

        $this->dotenv->clearConverters();
        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Null
     */
    public function testRuleNotNull()
    {
        $this->dotenv->rule('KEY')
            ->isNotNull();

        $this->dotenv->parse("KEY=1");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Email
     */
    public function testRuleEmail()
    {
        $this->dotenv->rule('KEY')
            ->isEmail();

        $this->dotenv->parse("KEY=email@google.com");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Ip
     */
    public function testRuleIp()
    {
        $this->dotenv->rule('K1', 'K2')
            ->isIp();

        $this->dotenv->parse("K1=8.8.8.8 \n K2=ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Ipv4
     */
    public function testRuleIpv4()
    {
        $this->dotenv->rule('KEY')
            ->isIpv4();

        $this->dotenv->parse("KEY=8.8.8.8");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is Ipv6
     */
    public function testRuleIpv6()
    {
        $this->dotenv->rule('KEY')
            ->isIpv6();

        $this->dotenv->parse("KEY=ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is MAC
     */
    public function testRuleMac()
    {
        $this->dotenv->rule('KEY')
            ->isMac();

        $this->dotenv->parse("KEY=00-00-00-00-00-00");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Rule Is URL
     */
    public function testRuleUrl()
    {
        $this->dotenv->rule('KEY')
            ->isUrl();

        $this->dotenv->parse("KEY=https://google.com");

        $this->dotenv->clearRules();

        $this->assertTrue(true);
    }

    /**
     * @testdox Validate: Create by bootstrap with adaptor index
     */
    public function testBootstrapAdaptor()
    {
        $adaptor = [
            'ApacheGetEnv'   => new \DotEnv\Adaptor\ApacheGetEnv(),
            'EnvSuperGlobal' => new \DotEnv\Adaptor\EnvSuperGlobal()
        ];

        $dotenv = DotEnv::bootstrap([
            'adaptor' => $adaptor
        ]);

        $this->assertEquals(
            $adaptor,
            $dotenv->getAdaptors()
        );
    }

    /**
     * @testdox Validate: Create by bootstrap with scheme index with rule
     */
    public function testBootstrapSchemeRule()
    {
        $dotenv = DotEnv::bootstrap([
            'scheme' => [
                'KEY1' => \DotEnv\Rule::IS_REQUIRED,
                'KEY2' => [\DotEnv\Rule::IS_RANGE_VALUE => [1, 9]],
            ]
        ]);

        $this->assertEquals(
            [
                'KEY1' => [
                    \DotEnv\Rule::IS_REQUIRED => true
                ],
                'KEY2' => [
                    \DotEnv\Rule::IS_MIN_VALUE => 1,
                    \DotEnv\Rule::IS_MAX_VALUE => 9,
                ]
            ],
            $dotenv->getRules()
        );
    }

    /**
     * @testdox Validate: Create by bootstrap with scheme index with converter
     */
    public function testBootstrapSchemeConverter()
    {
        $dotenv = DotEnv::bootstrap([
            'scheme' => [
                'KEY1' => \DotEnv\Converter::TO_INT,
                'KEY2' => [ \DotEnv\Converter::TO_BOOL ],
            ]
        ]);

        $this->assertEquals(
            [
                'KEY1' => [ \DotEnv\Converter::TO_INT ],
                'KEY2' => [ \DotEnv\Converter::TO_BOOL ],
            ],
            $dotenv->getConverters()
        );
    }

    /**
     * @testdox Validate: Create by bootstrap with parse index as string
     */
    public function testBootstrapParseString()
    {
        $dotenv = DotEnv::bootstrap([
            'parse'   => "KEY1=AAA \n KEY2=BBB \n KEY3='CCC\nDDD'"
        ]);

        $this->assertEquals(
            [ 'KEY1' => 'AAA', 'KEY2' => 'BBB', 'KEY3' => "CCC\nDDD" ],
            $dotenv->all()
        );
    }

    /**
     * @testdox Validate: Create by bootstrap with parse index as array
     */
    public function testBootstrapParseArray()
    {
        $dotenv = DotEnv::bootstrap([
            'parse'   => [
                'KEY1=AAA',
                'KEY2' => 'BBB',
                'KEY3' => "CCC\n\"'DDD"
            ]
        ]);

        $this->assertEquals(
            [ 'KEY1' => 'AAA', 'KEY2' => 'BBB', 'KEY3' => "CCC\n\"'DDD" ],
            $dotenv->all()
        );
    }
}
