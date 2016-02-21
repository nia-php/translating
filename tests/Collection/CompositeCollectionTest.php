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
namespace Test\Nia\Translating\Collection;

use PHPUnit_Framework_TestCase;
use Nia\Translating\Collection\CompositeCollection;
use Nia\Translating\Collection\Collection;
use Nia\Collection\Map\StringMap\MapInterface;

/**
 * Unit test for \Nia\Translating\Collection\CompositeCollection.
 */
class CompositeCollectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Translating\Collection\CompositeCollection::getLocale
     */
    public function testGetLocale()
    {
        $collection = new CompositeCollection('de_DE');

        $this->assertSame('de_DE', $collection->getLocale());
    }

    /**
     * @covers \Nia\Translating\Collection\CompositeCollection::has
     */
    public function testHas()
    {
        $collection = new CompositeCollection('de_DE', [
            new Collection('de_DE', [
                'foobar' => $this->getMock(MapInterface::class)
            ])
        ]);

        $this->assertSame(true, $collection->has('foobar'));
        $this->assertSame(false, $collection->has('foobaz'));
    }

    /**
     * @covers \Nia\Translating\Collection\CompositeCollection::get
     */
    public function testGet()
    {
        $foobar = $this->getMock(MapInterface::class);

        $collection = new CompositeCollection('de_DE', [
            new Collection('de_DE', [
                'foobar' => $foobar
            ])
        ]);

        $this->assertSame($foobar, $collection->get('foobar'));
    }

    /**
     * @covers \Nia\Translating\Collection\CompositeCollection::get
     */
    public function testGetException()
    {
        $this->setExpectedException(\OutOfBoundsException::class, 'Message "foobar" is not contained in this collection.');

        $collection = new CompositeCollection('de_DE');

        $collection->get('foobar');
    }

    /**
     * @covers \Nia\Translating\Collection\CompositeCollection::addCollection
     * @covers \Nia\Translating\Collection\CompositeCollection::getCollections
     */
    public function testAddCollectionGetCollections()
    {
        $foobar = new Collection('de_DE', []);
        $foobaz = new Collection('de_DE', []);

        $collection = new CompositeCollection('de_DE', [
            $foobar
        ]);

        $this->assertSame([
            $foobar
        ], $collection->getCollections());

        $collection->addCollection($foobaz);
        $this->assertSame([
            $foobar,
            $foobaz
        ], $collection->getCollections());
    }

    /**
     * @covers \Nia\Translating\Collection\CompositeCollection::addCollection
     */
    public function testAddCollectionException()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'Passed collection uses the locale "en_US", but "de_DE" is required.');

        $collection = new CompositeCollection('de_DE');
        $collection->addCollection(new Collection('en_US', []));
    }

    /**
     * @covers \Nia\Translating\Collection\CompositeCollection::getIterator
     */
    public function testGetIterator()
    {
        $foobar = new Collection('de_DE', []);
        $foobaz = new Collection('de_DE', []);

        $collection = new CompositeCollection('de_DE', [
            $foobar
        ]);

        $this->assertSame([
            $foobar
        ], iterator_to_array($collection->getIterator()));

        $collection->addCollection($foobaz);
        $this->assertSame([
            $foobar,
            $foobaz
        ], iterator_to_array($collection->getIterator()));
    }
}
