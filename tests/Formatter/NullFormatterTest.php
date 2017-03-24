<?php
/*
 * This file is part of the nia framework architecture.
 *
 * (c) Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);
namespace Test\Nia\Translating\Formatter;

use PHPUnit\Framework\TestCase;
use Nia\Translating\Formatter\NullFormatter;
use Nia\Translating\Translator\TranslatorInterface;
use Nia\Collection\Map\StringMap\MapInterface;

/**
 * Unit test for \Nia\Translating\Formatter\NullFormatter.
 */
class NullFormatterTest extends TestCase
{

    /**
     * @covers \Nia\Translating\Formatter\NullFormatter::format
     */
    public function testFormat()
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $locale = 'de_DE';
        $value = 'foo bar';
        $context = $this->createMock(MapInterface::class);

        $formatter = new NullFormatter();

        $this->assertSame($value, $formatter->format($translator, $locale, $value, $context));
    }
}
