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
namespace Nia\Translating\Translator;

use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Collection\Map\StringMap\Map;
use Nia\Translating\Filter\FilterInterface;

/**
 * Translator implementation using a native map.
 */
class MappedTranslator implements TranslatorInterface
{

    /**
     * The used locale hierarchy.
     *
     * @var string[]
     */
    private $localeHierarchy = [];

    /**
     * The used message filter.
     *
     * @var FilterInterface
     *
     */
    private $filter = null;

    /**
     * Native map with messages associated with a locale.
     *
     * @var mixed[]
     */
    private $messages = [];

    /**
     * Constructor.
     *
     * @param string[] $localeHierarchy
     *            The used locale hierarchy.
     * @param FilterInterface $filter
     *            The used message filter.
     * @param mixed[] $messages
     *            Native map with messages associated with a locale.
     */
    public function __construct(array $localeHierarchy, FilterInterface $filter, array $messages)
    {
        $this->localeHierarchy = $localeHierarchy;
        $this->filter = $filter;
        $this->messages = $messages;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Translator\TranslatorInterface::setLocaleHierarchy($localeHierarchy)
     */
    public function setLocaleHierarchy(array $localeHierarchy): TranslatorInterface
    {
        $this->localeHierarchy = $localeHierarchy;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Translator\TranslatorInterface::getLocaleHierarchy()
     */
    public function getLocaleHierarchy(): array
    {
        return $this->localeHierarchy;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Translator\TranslatorInterface::translate($messageId, $value, $context, $locale)
     */
    public function translate(string $messageId, int $value = null, MapInterface $context = null, string $locale = null): string
    {
        $context = $context ?? new Map();

        $localeHierarchy = array_merge([
            $locale
        ], $this->localeHierarchy);

        foreach ($localeHierarchy as $locale) {
            if (! array_key_exists($locale, $this->messages)) {
                continue;
            }

            if (! array_key_exists($messageId, $this->messages[$locale])) {
                continue;
            }

            $message = $this->messages[$locale][$messageId];

            return $this->filter->filter($this, $locale, $message, $context);
        }

        throw new \OutOfBoundsException(sprintf('Message "%s" is not contained in this translator.', $messageId));
    }
}
