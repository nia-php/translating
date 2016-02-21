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
use Nia\Translating\Collection\Collection;
use Nia\Collection\Map\StringMap\MapInterface;

/**
 * Unit test for \Nia\Translating\Collection\Collection.
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Translating\Collection\Collection::getLocale
     */
    public function testGetLocale()
    {
        $collection = new Collection('de_DE', []);

        $this->assertSame('de_DE', $collection->getLocale());
    }

    /**
     * @covers \Nia\Translating\Collection\Collection::has
     */
    public function testHas()
    {
        $collection = new Collection('de_DE', [
            'foobar' => $this->getMock(MapInterface::class)
        ]);

        $this->assertSame(true, $collection->has('foobar'));
        $this->assertSame(false, $collection->has('baz'));
    }

    /**
     * @covers \Nia\Translating\Collection\Collection::get
     */
    public function testGet()
    {
        $message = $this->getMock(MapInterface::class);

        $collection = new Collection('de_DE', [
            'foobar' => $message
        ]);

        $this->assertSame($message, $collection->get('foobar'));
    }

    /**
     * @covers \Nia\Translating\Collection\Collection::get
     */
    public function testGetException()
    {
        $this->setExpectedException(\OutOfBoundsException::class, 'Message "foobar" is not contained in this collection.');

        $collection = new Collection('de_DE', []);

        $collection->get('foobar');
    }

    /**
     * @covers \Nia\Translating\Collection\Collection::getIterator
     */
    public function testGetIterator()
    {
        $expected = [
            'foobar' => $this->getMock(MapInterface::class),
            'foobaz' => $this->getMock(MapInterface::class)
        ];

        $collection = new Collection('de_DE', $expected);

        $this->assertSame($expected, iterator_to_array($collection->getIterator()));
    }
}
