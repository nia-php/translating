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
namespace Test\Nia\Translating\Translator;

use PHPUnit_Framework_TestCase;
use Nia\Translating\Translator\CollectionTranslator;
use Nia\Translating\Selector\SelectorInterface;
use Nia\Translating\Filter\FilterInterface;
use Nia\Translating\Collection\Collection;
use Nia\Translating\Collection\CompositeCollection;
use Nia\Translating\Selector\PluralRuleSelector;
use Nia\Translating\Filter\ValueFormatterFilter;
use Nia\Collection\Map\StringMap\Map;

/**
 * Unit test for \Nia\Translating\Translator\CollectionTranslator.
 */
class CollectionTranslatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Translating\Translator\CollectionTranslator::setLocaleHierarchy
     * @covers \Nia\Translating\Translator\CollectionTranslator::getLocaleHierarchy
     */
    public function testSetGetLocaleHierarchy()
    {
        $localeHierarchy = [
            'de_AT',
            'de_DE',
            'en_US'
        ];

        $selector = $this->getMock(SelectorInterface::class);
        $filter = $this->getMock(FilterInterface::class);

        $translator = new CollectionTranslator($localeHierarchy, $selector, $filter, []);

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
     * @covers \Nia\Translating\Translator\CollectionTranslator::translate
     */
    public function testTranslate()
    {
        $localeHierarchy = [
            'de_DE',
            'en_US'
        ];

        $translator = new CollectionTranslator($localeHierarchy, new PluralRuleSelector(), new ValueFormatterFilter([]), [
            new Collection('de_DE', [
                'foobar' => new Map([
                    '0' => 'Du hast {{ value }} neue Nachricht.',
                    '1' => 'Du hast {{ value }} neue Nachrichten.'
                ])
            ]),
            new Collection('en_US', [
                'foobar' => new Map([
                    '0' => 'You have {{ value }} new message.',
                    '1' => 'You have {{ value }} new messages.'
                ])
            ])
        ]);

        // no selector
        // -----------------------------------
        $expected = 'Du hast value neue Nachricht.';
        $actual = $translator->translate('foobar', null);

        $this->assertSame($expected, $actual);

        // singulars
        // -----------------------------------

        // singular: no context
        $expected = 'Du hast value neue Nachricht.';
        $actual = $translator->translate('foobar', 1);

        $this->assertSame($expected, $actual);

        // singular: with context
        $expected = 'Du hast 5678 neue Nachricht.';
        $actual = $translator->translate('foobar', 1, new Map([
            'value' => '5678'
        ]));

        $this->assertSame($expected, $actual);

        // singular: no context, overwrite locale
        $expected = 'You have value new message.';
        $actual = $translator->translate('foobar', 1, null, 'en_US');

        $this->assertSame($expected, $actual);

        // singular: with context, overwrite locale
        $expected = 'You have 5678 new message.';
        $actual = $translator->translate('foobar', 1, new Map([
            'value' => '5678'
        ]), 'en_US');

        $this->assertSame($expected, $actual);

        // plurals
        // -----------------------------------

        // plural: no context
        $expected = 'Du hast value neue Nachrichten.';
        $actual = $translator->translate('foobar', 1234);

        $this->assertSame($expected, $actual);

        // plural: with context
        $expected = 'Du hast 5678 neue Nachrichten.';
        $actual = $translator->translate('foobar', 1234, new Map([
            'value' => '5678'
        ]));

        $this->assertSame($expected, $actual);

        // plural: no context, overwrite locale
        $expected = 'You have value new messages.';
        $actual = $translator->translate('foobar', 1234, null, 'en_US');

        $this->assertSame($expected, $actual);

        // plural: with context, overwrite locale
        $expected = 'You have 5678 new messages.';
        $actual = $translator->translate('foobar', 1234, new Map([
            'value' => '5678'
        ]), 'en_US');

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \Nia\Translating\Translator\CollectionTranslator::addCollection
     * @covers \Nia\Translating\Translator\CollectionTranslator::getCollections
     */
    public function testAddCollectionGetCollections()
    {
        $localeHierarchy = [
            'de_AT',
            'de_DE',
            'en_US'
        ];

        $selector = $this->getMock(SelectorInterface::class);
        $filter = $this->getMock(FilterInterface::class);

        $collectionDeDe1 = new Collection('de_DE', []);
        $collectionDeDe2 = new Collection('de_DE', []);
        $collectionDeAt1 = new Collection('de_AT', []);

        $translator = new CollectionTranslator($localeHierarchy, $selector, $filter, [
            $collectionDeAt1
        ]);

        $this->assertEquals([
            'de_AT' => new CompositeCollection('de_AT', [
                $collectionDeAt1
            ])
        ], $translator->getCollections());

        $translator->addCollection($collectionDeDe2);
        $translator->addCollection($collectionDeDe1);

        $this->assertEquals([
            'de_AT' => new CompositeCollection('de_AT', [
                $collectionDeAt1
            ]),
            'de_DE' => new CompositeCollection('de_DE', [
                $collectionDeDe2,
                $collectionDeDe1
            ])
        ], $translator->getCollections());
    }
}
