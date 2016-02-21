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
namespace Nia\Translating\Translator;

use Nia\Collection\Map\StringMap\MapInterface;

/**
 * Interface for translator implementations.
 */
interface TranslatorInterface
{

    /**
     * Sets the locale hierarchy.
     *
     *
     * @param string[] $localeHierarchy
     *            The locale hierarchy.
     * @return TranslatorInterface Reference to this instance.
     */
    public function setLocaleHierarchy(array $localeHierarchy): TranslatorInterface;

    /**
     * Returns the used locale hierarchy.
     *
     * @return string[] The used locale hierarchy.
     */
    public function getLocaleHierarchy(): array;

    /**
     * Returns a requested translation by the passed message id.
     *
     * @param string $messageId
     *            The message id of the translation.
     * @param int $value
     *            Optional primary value to detect the plural form.
     * @param MapInterface $context
     *            Optional map with values.
     * @param string $locale
     *            Optional locale force.
     * @return string The requested translation.
     */
    public function translate(string $messageId, int $value = null, MapInterface $context = null, string $locale = null): string;
}
