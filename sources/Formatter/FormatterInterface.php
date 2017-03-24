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
 * Interface for value formatters used by the ValueFormatterFilter implementation.
 */
interface FormatterInterface
{

    /**
     * Formats a value.
     *
     * @param TranslatorInterface $translator
     *            The calling translator.
     * @param string $locale
     *            The used locale.
     * @param string $value
     *            The value to format.
     * @param MapInterface $context
     *            Argument context.
     * @return string The formatted value.
     */
    public function format(TranslatorInterface $translator, string $locale, string $value, MapInterface $context): string;
}
