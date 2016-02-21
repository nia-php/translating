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
 * Interface for message selectors.
 * Message selectors are used to detect the best fitting plural form by using locale and a primary value.
 */
interface SelectorInterface
{

    /**
     * Chooses the best fitting plural form from the passed messages by using a locale and a primary value.
     *
     * @param string $locale
     *            The used locale.
     * @param MapInterface $messages
     *            Map with plural forms.
     * @param int $value
     *            The primary value.
     * @return string The choosen plural from.
     */
    public function choose(string $locale, MapInterface $messages, int $value): string;
}
