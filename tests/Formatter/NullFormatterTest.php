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
namespace Test\Nia\Translating\Formatter;

use PHPUnit_Framework_TestCase;
use Nia\Translating\Formatter\NullFormatter;
use Nia\Translating\Translator\TranslatorInterface;
use Nia\Collection\Map\StringMap\MapInterface;

/**
 * Unit test for \Nia\Translating\Formatter\NullFormatter.
 */
class NullFormatterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Translating\Formatter\NullFormatter::format
     */
    public function testFormat()
    {
        $translator = $this->getMock(TranslatorInterface::class);
        $locale = 'de_DE';
        $value = 'foo bar';
        $context = $this->getMock(MapInterface::class);

        $formatter = new NullFormatter();

        $this->assertSame($value, $formatter->format($translator, $locale, $value, $context));
    }
}
