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
namespace Nia\Translating\Selector;

use Nia\Collection\Map\StringMap\MapInterface;

/**
 * Message selector which returns the first entry of the message map.
 */
class NullSelector implements SelectorInterface
{

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Selector\SelectorInterface::choose($locale, $messages, $value)
     */
    public function choose(string $locale, MapInterface $messages, int $value): string
    {
        return $messages->getIterator()->current();
    }
}
