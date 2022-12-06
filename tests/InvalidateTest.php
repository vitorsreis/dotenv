<?php
/**
 * This file is part of PHP DotEnv
 * @author Vitor Reis <vitor@d5w.com.br>
 */

namespace DotEnv\Test;

use DotEnv\DotEnv;
use DotEnv\Exception\Loader;
use DotEnv\Exception\Syntax;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidateTest
 * @package DotEnv\Tests
 * @testdox Invalidate values test
 */
class InvalidateTest extends TestCase
{
    private $dotenv;
    public function __construct($name = null, $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->dotenv = new DotEnv();
        $this->dotenv->enableDebug();
    }

    /**
     * @testdox Invalidate: File not found
     */
    public function testFileNotFound()
    {
        $this->expectException(Loader::class);
        $this->dotenv->load(uniqid());
    }

    /**
     * @testdox Invalidate: Key with empty value
     */
    public function testKeyWithEmptyValue()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('KEY');
    }

    /**
     * @testdox Invalidate: Empty key with value
     */
    public function testEmptyKeyWithValue()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('=VALUE');
    }

    /**
     * @testdox Invalidate: Empty key and value
     */
    public function testEmptyKeyAndValue()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('=');
    }

    /**
     * @testdox Invalidate: Key with space
     */
    public function testKeyWithSpace()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('KEY KEY=VALUE');
    }

    /**
     * @testdox Invalidate: Key with hyphen
     */
    public function testKeyWithHyphen()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('K-EY=VALUE');
    }

    /**
     * @testdox Invalidate: Key with invalid character [^a-zA-Z0-9_]
     */
    public function testKeyWithInvalidCharacter()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('AE!Y=VALUE');
    }

    /**
     * @testdox Invalidate: Key not started with "A-Z" or "_"
     */
    public function testKeyStart()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('1EY=VALUE');
    }

    /**
     * @testdox Invalidate: Value with space not quoted
     */
    public function testValueWithSpaceNotQuoted()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('KEY=VAL UE');
    }

    /**
     * @testdox Invalidate: Start quote not closed
     */
    public function testStartQuoteNotClosed()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse("1EY='VALUE\'");
    }

    /**
     * @testdox Invalidate: Start double quote not closed
     */
    public function testStartDoubleQuoteNotClosed()
    {
        $this->expectException(Syntax::class);
        $this->dotenv->parse('1EY="VALUE\"');
    }

    /**
     * @testdox Validate: Convert To Bool
     */
    public function testConvertBool()
    {
        $this->expectException(Loader::class);

        $this->dotenv->convert('KEY')->toBool();

        $this->dotenv->parse('KEY=1.00');

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Convert To Int
     */
    public function testConvertInt()
    {
        $this->expectException(Loader::class);

        $this->dotenv->convert('KEY')->toInt();

        $this->dotenv->parse('KEY=1.00');

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Convert To Int
     */
    public function testConvertFloat()
    {
        $this->expectException(Loader::class);

        $this->dotenv->convert('KEY')->toFloat();

        $this->dotenv->parse('KEY=A');

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Rule Is Regex
     */
    public function testInvalidationRegex()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isRegex('/[\D]/');

        $this->dotenv->parse("KEY=100");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Required
     */
    public function testValidateRequired()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule(uniqid())
            ->isRequired();

        $this->dotenv->parse('#');

        $this->dotenv->clearConverters();
    }

    /**
     * @testdox Validate: Rule Is Bool
     */
    public function testInvalidationBool()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('K1')
            ->isBool();

        $this->dotenv->parse("K1=A");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Int
     */
    public function testInvalidationInt()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isInt();

        $this->dotenv->parse("KEY=A");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Float
     */
    public function testInvalidationFloat()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isFloat();

        $this->dotenv->parse("KEY=A");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Min Value
     */
    public function testInvalidationMinValue()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isMinValue(10);

        $this->dotenv->parse("KEY=9");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Max Value
     */
    public function testInvalidationMaxValue()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isMaxValue(10);

        $this->dotenv->parse("KEY=11");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Range Value
     */
    public function testInvalidationRangeValue()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isRangeValue(10, 12);

        $this->dotenv->parse("KEY=15");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Min Length
     */
    public function testInvalidationMinLength()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isMinLength(3);

        $this->dotenv->parse("KEY=AA");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Max Length
     */
    public function testInvalidationMaxLength()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isMaxLength(3);

        $this->dotenv->parse("KEY=AAAA");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Range Length
     */
    public function testInvalidationRangeLength()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isRangeLength(2, 4);

        $this->dotenv->parse("KEY=A");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Empty
     */
    public function testInvalidationEmpty()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isEmpty();

        $this->dotenv->parse("KEY=A");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Not Empty
     */
    public function testInvalidationNotEmpty()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isNotEmpty();

        $this->dotenv->parse("KEY=");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Null
     */
    public function testInvalidationNull()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isNull();

        $this->dotenv->parse("KEY=A");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Null
     */
    public function testInvalidationNotNull()
    {
        $this->expectException(Loader::class);

        $this->dotenv->convert('KEY')
            ->toBoolOrNull();

        $this->dotenv->rule('KEY')
            ->isNotNull();

        $this->dotenv->parse("KEY=A");

        $this->dotenv->clearConverters();
        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Email
     */
    public function testInvalidationEmail()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isEmail();

        $this->dotenv->parse("KEY=email@google");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Ip
     */
    public function testInvalidationIp()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('K1')
            ->isIp();

        $this->dotenv->parse("K1=8.8.8.A");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Ipv4
     */
    public function testInvalidationIpv4()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isIpv4();

        $this->dotenv->parse("KEY=256.8.8.8");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is Ipv6
     */
    public function testInvalidationIpv6()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isIpv6();

        $this->dotenv->parse("KEY=affff:ffff:ffff:ffff:ffff:ffff:ffff:ffff");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is MAC
     */
    public function testInvalidationMac()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isMac();

        $this->dotenv->parse("KEY=A00-00-00-00-00-00");

        $this->dotenv->clearRules();
    }

    /**
     * @testdox Validate: Rule Is URL
     */
    public function testInvalidationUrl()
    {
        $this->expectException(Loader::class);

        $this->dotenv->rule('KEY')
            ->isUrl();

        $this->dotenv->parse("KEY=https//google.com");

        $this->dotenv->clearRules();
    }
}
