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
use Nia\Translating\Filter\FilterInterface;
use Nia\Translating\Filter\ValueFormatterFilter;
use Nia\Collection\Map\StringMap\Map;
use Nia\Translating\Translator\CompositeTranslator;
use Nia\Translating\Translator\MappedTranslator;
use Nia\Translating\Translator\TranslatorInterface;

/**
 * Unit test for \Nia\Translating\Translator\CompositeTranslator.
 */
class CompositeTranslatorTest extends TestCase
{

    /**
     * @covers \Nia\Translating\Translator\CompositeTranslator::addTranslator
     * @covers \Nia\Translating\Translator\CompositeTranslator::getTranslators
     */
    public function testAddTranslatorGetTranslators()
    {
        $translator1 = $this->createMock(TranslatorInterface::class);
        $translator2 = $this->createMock(TranslatorInterface::class);
        $translator3 = $this->createMock(TranslatorInterface::class);

        $composite = new CompositeTranslator([
            $translator1,
            $translator2
        ]);

        $this->assertSame([
            $translator1,
            $translator2
        ], $composite->getTranslators());

        $composite->addTranslator($translator3);

        $this->assertSame([
            $translator1,
            $translator2,
            $translator3
        ], $composite->getTranslators());
    }

    /**
     * @covers \Nia\Translating\Translator\CompositeTranslator::setLocaleHierarchy
     * @covers \Nia\Translating\Translator\CompositeTranslator::getLocaleHierarchy
     */
    public function testSetGetLocaleHierarchy()
    {
        $localeHierarchy = [
            'de_AT',
            'de_DE',
            'en_US'
        ];

        // test without assigned translators.
        $translator = new CompositeTranslator();
        $this->assertSame([], $translator->getLocaleHierarchy());
        $this->assertSame($translator, $translator->setLocaleHierarchy($localeHierarchy));
        $this->assertSame([], $translator->getLocaleHierarchy());

        // test with assigned translators.
        $filter = $this->createMock(FilterInterface::class);

        $translator = new CompositeTranslator([
            new MappedTranslator([
                'fr_FR'
            ], $filter, [])
        ]);

        $this->assertSame([
            'fr_FR'
        ], $translator->getLocaleHierarchy());

        $translator->addTranslator(new MappedTranslator($localeHierarchy, $filter, []));

        $this->assertSame([
            'fr_FR',
            'de_AT',
            'de_DE',
            'en_US'
        ], $translator->getLocaleHierarchy());

        $this->assertSame($translator, $translator->setLocaleHierarchy($localeHierarchy));
        $this->assertSame($localeHierarchy, $translator->getLocaleHierarchy());
    }

    /**
     * @covers \Nia\Translating\Translator\CompositeTranslator::translate
     */
    public function testTranslate()
    {
        $localeHierarchy = [
            'de_DE',
            'en_US'
        ];

        $translator = new CompositeTranslator([
            new MappedTranslator($localeHierarchy, new ValueFormatterFilter([]), [
                'de_DE' => [
                    'foobar' => 'Du hast {{ value }} neue Nachrichten.'
                ],
                'en_US' => [
                    'foobar' => 'You have {{ value }} new messages.'
                ]
            ]),
            new MappedTranslator($localeHierarchy, new ValueFormatterFilter([]), [
                'de_DE' => [
                    'foobar2' => 'Du hast {{ value }} neue Nachrichten erhalten.'
                ]
            ])
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

        // next translator in composite
        $expected = 'Du hast value neue Nachrichten erhalten.';
        $actual = $translator->translate('foobar2', 1);

        $this->assertSame($expected, $actual);
    }
}
