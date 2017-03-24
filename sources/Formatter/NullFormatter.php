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
namespace Nia\Translating\Formatter;

use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Translating\Translator\TranslatorInterface;

/**
 * Null object formatter implementation.
 */
class NullFormatter implements FormatterInterface
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Formatter\FormatterInterface::format($translator, $locale, $value, $context)
     */
    public function format(TranslatorInterface $translator, string $locale, string $value, MapInterface $context): string
    {
        return $value;
    }
}
