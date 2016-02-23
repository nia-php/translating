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
namespace Test\Nia\Translating\Selector;

use PHPUnit_Framework_TestCase;
use Nia\Translating\Selector\NullSelector;
use Nia\Collection\Map\StringMap\Map;

/**
 * Unit test for \Nia\Translating\Selector\NullSelector.
 */
class NullSelectorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Translating\Selector\NullSelector::choose
     */
    public function testChoose()
    {
        $selector = new NullSelector();

        $messages = new Map([
            'a',
            'b',
            'c',
            'd'
        ]);

        $this->assertSame('a', $selector->choose('de_DE', $messages, 0));
        $this->assertSame('a', $selector->choose('de_DE', $messages, 1));
        $this->assertSame('a', $selector->choose('de_DE', $messages, 2));
        $this->assertSame('a', $selector->choose('de_DE', $messages, 3));
        $this->assertSame('a', $selector->choose('de_DE', $messages, 4));

        $this->assertSame('a', $selector->choose('xx_XX', $messages, 0));
        $this->assertSame('a', $selector->choose('xx_XX', $messages, 1));
        $this->assertSame('a', $selector->choose('xx_XX', $messages, 2));
        $this->assertSame('a', $selector->choose('xx_XX', $messages, 3));
        $this->assertSame('a', $selector->choose('xx_XX', $messages, 4));

        $this->assertSame('a', $selector->choose('bs_Latn_BA', $messages, 0));
        $this->assertSame('a', $selector->choose('bs_Latn_BA', $messages, 1));
        $this->assertSame('a', $selector->choose('bs_Latn_BA', $messages, 2));
        $this->assertSame('a', $selector->choose('bs_Latn_BA', $messages, 3));
        $this->assertSame('a', $selector->choose('bs_Latn_BA', $messages, 4));
    }
}