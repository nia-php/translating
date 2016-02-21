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
namespace Nia\Translating\Filter;

use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Translating\Translator\TranslatorInterface;

/**
 * Interface for message filter implementations.
 * Message filters are used to post process the translated messages of a translator.
 */
interface FilterInterface
{

    /**
     * Filters the translated message.
     *
     * @param TranslatorInterface $translator
     *            The translator which calls this filter.
     * @param string $locale
     *            The used locale.
     * @param string $message
     *            The message to filter.
     * @param MapInterface $context
     *            The translation context.
     * @return string The filtered message.
     */
    public function filter(TranslatorInterface $translator, string $locale, string $message, MapInterface $context): string;
}