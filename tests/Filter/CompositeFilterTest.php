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
use Nia\Translating\Filter\CompositeFilter;
use Nia\Translating\Filter\FilterInterface;
use Nia\Translating\Translator\TranslatorInterface;
use Nia\Collection\Map\StringMap\MapInterface;

/**
 * Unit test for \Nia\Translating\Filter\CompositeFilter.
 */
class CompositeFilterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Translating\Filter\CompositeFilter::__construct
     * @covers \Nia\Translating\Filter\CompositeFilter::filter
     * @covers \Nia\Translating\Filter\CompositeFilter::addFilter
     * @covers \Nia\Translating\Filter\CompositeFilter::getFilters
     */
    public function testFilter()
    {
        $filter1 = new class() implements FilterInterface {

            public function filter(TranslatorInterface $translator, string $locale, string $message, MapInterface $context): string
            {
                return '>' . $message . '<';
            }
        };

        $filter2 = new class() implements FilterInterface {

            public function filter(TranslatorInterface $translator, string $locale, string $message, MapInterface $context): string
            {
                return '|' . $message . '|';
            }
        };

        $filter = new CompositeFilter([
            $filter1
        ]);

        $this->assertSame([
            $filter1
        ], $filter->getFilters());

        $filter->addFilter($filter2);
        $filter->addFilter($filter1);

        $this->assertSame([
            $filter1,
            $filter2,
            $filter1
        ], $filter->getFilters());

        $translator = $this->getMock(TranslatorInterface::class);
        $locale = 'de_DE';
        $context = $this->getMock(MapInterface::class);

        $this->assertSame('>|> foo bar <|<', $filter->filter($translator, $locale, ' foo bar ', $context));
    }
}
