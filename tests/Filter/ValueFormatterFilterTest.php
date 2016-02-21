<?php
/*
 * This file is part of the nia framework architecture.
 *
 * (c) 2016 - Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);
namespace Test\Nia\Translating\Filter;

use PHPUnit_Framework_TestCase;
use Nia\Translating\Filter\ValueFormatterFilter;
use Nia\Translating\Translator\TranslatorInterface;
use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Translating\Formatter\FormatterInterface;
use Nia\Collection\Map\StringMap\Map;

/**
 * Unit test for \Nia\Translating\Filter\ValueFormatterFilter.
 */
class ValueFormatterFilterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Translating\Filter\ValueFormatterFilter::__construct
     * @covers \Nia\Translating\Filter\ValueFormatterFilter::filter
     */
    public function testFilter()
    {
        $formatter = new class() implements FormatterInterface {

            public function format(TranslatorInterface $translator, string $locale, string $value, MapInterface $context): string
            {
                ob_start();
                var_dump($locale, $value, iterator_to_array($context->getIterator()));
                return ob_get_clean();
            }
        };

        $filter = new ValueFormatterFilter([
            'dump' => $formatter
        ]);

        $translator = $this->getMock(TranslatorInterface::class);
        $locale = 'de_DE';

        // test empty context
        $context = new Map();
        $message = 'I love my {{ 12345 }} bread crumbs.';
        $expected = 'I love my 12345 bread crumbs.';

        $this->assertSame($expected, $filter->filter($translator, $locale, $message, $context));

        // test empty context with user defined formatter
        $context = new Map();
        $message = 'I love my {{ 12345 | dump }} bread crumbs.';
        $expected = 'I love my string(5) "de_DE"
string(5) "12345"
array(0) {
}
 bread crumbs.';

        $this->assertSame($expected, $filter->filter($translator, $locale, $message, $context));

        // test empty context with user defined formatter and arguments
        $context = new Map();
        $message = 'I love my {{ 12345 | dump (locale=en_US, currency=EUR) }} bread crumbs.';
        $expected = 'I love my string(5) "de_DE"
string(5) "12345"
array(2) {
  ["locale"]=>
  string(5) "en_US"
  ["currency"]=>
  string(3) "EUR"
}
 bread crumbs.';

        $this->assertSame($expected, $filter->filter($translator, $locale, $message, $context));

        // test context
        $context = new Map([
            'value' => '12345.678'
        ]);
        $message = 'I love my {{ value }} bread crumbs.';
        $expected = 'I love my 12345.678 bread crumbs.';

        $this->assertSame($expected, $filter->filter($translator, $locale, $message, $context));

        // test context with user defined formatter
        $context = new Map([
            'value' => '12345.678'
        ]);
        $message = 'I love my {{ value | dump }} bread crumbs.';
        $expected = 'I love my string(5) "de_DE"
string(9) "12345.678"
array(0) {
}
 bread crumbs.';

        $this->assertSame($expected, $filter->filter($translator, $locale, $message, $context));

        // test context with user defined formatter and arguments
        $context = new Map([
            'value' => '12345.678'
        ]);
        $message = 'I love my {{ value | dump (locale=en_US, currency=EUR) }} bread crumbs.';
        $expected = 'I love my string(5) "de_DE"
string(9) "12345.678"
array(2) {
  ["locale"]=>
  string(5) "en_US"
  ["currency"]=>
  string(3) "EUR"
}
 bread crumbs.';

        $this->assertSame($expected, $filter->filter($translator, $locale, $message, $context));
    }
}
