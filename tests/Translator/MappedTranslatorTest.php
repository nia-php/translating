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
namespace Test\Nia\Translating\Translator;

use PHPUnit\Framework\TestCase;
use Nia\Translating\Translator\CollectionTranslator;
use Nia\Translating\Selector\SelectorInterface;
use Nia\Translating\Filter\FilterInterface;
use Nia\Translating\Collection\Collection;
use Nia\Translating\Collection\CompositeCollection;
use Nia\Translating\Selector\PluralRuleSelector;
use Nia\Translating\Filter\ValueFormatterFilter;
use Nia\Collection\Map\StringMap\Map;
use Nia\Translating\Translator\MappedTranslator;

/**
 * Unit test for \Nia\Translating\Translator\MappedTranslator.
 */
class MappedTranslatorTest extends TestCase
{

    /**
     * @covers \Nia\Translating\Translator\MappedTranslator::setLocaleHierarchy
     * @covers \Nia\Translating\Translator\MappedTranslator::getLocaleHierarchy
     */
    public function testSetGetLocaleHierarchy()
    {
        $localeHierarchy = [
            'de_AT',
            'de_DE',
            'en_US'
        ];

        $filter = $this->createMock(FilterInterface::class);

        $translator = new MappedTranslator($localeHierarchy, $filter, []);

        $this->assertSame($localeHierarchy, $translator->getLocaleHierarchy());

        $localeHierarchy = [
            'de_AT',
            'de_CH',
            'de_DE',
            'en_US'
        ];

        $translator->setLocaleHierarchy($localeHierarchy);

        $this->assertSame($localeHierarchy, $translator->getLocaleHierarchy());
    }

    /**
     * @covers \Nia\Translating\Translator\MappedTranslator::translate
     */
    public function testTranslate()
    {
        $localeHierarchy = [
            'de_DE',
            'en_US'
        ];

        $translator = new MappedTranslator($localeHierarchy, new ValueFormatterFilter([]), [
            'de_DE' => [
                'foobar' => 'Du hast {{ value }} neue Nachrichten.'
            ],
            'en_US' => [
                'foobar' => 'You have {{ value }} new messages.'
            ]
        ]);

        // no selector
        $expected = 'Du hast value neue Nachrichten.';
        $actual = $translator->translate('foobar', null);

        $this->assertSame($expected, $actual);

        // no context
        $expected = 'Du hast value neue Nachrichten.';
        $actual = $translator->translate('foobar', 1);

        $this->assertSame($expected, $actual);

        // with context
        $expected = 'Du hast 5678 neue Nachrichten.';
        $actual = $translator->translate('foobar', 1, new Map([
            'value' => '5678'
        ]));

        $this->assertSame($expected, $actual);

        // no context, overwrite locale
        $expected = 'You have value new messages.';
        $actual = $translator->translate('foobar', 1, null, 'en_US');

        $this->assertSame($expected, $actual);

        // with context, overwrite locale
        $expected = 'You have 5678 new messages.';
        $actual = $translator->translate('foobar', 1, new Map([
            'value' => '5678'
        ]), 'en_US');

        $this->assertSame($expected, $actual);
    }
}
