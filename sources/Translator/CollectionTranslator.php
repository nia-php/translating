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
use Nia\Translating\Collection\CollectionInterface;
use Nia\Translating\Collection\CompositeCollectionInterface;
use Nia\Translating\Selector\SelectorInterface;
use Nia\Translating\Collection\CompositeCollection;
use Nia\Collection\Map\StringMap\Map;
use Nia\Translating\Filter\FilterInterface;
use Nia\Translating\Selector\NullSelector;

/**
 * Default collection translator implementation.
 */
class CollectionTranslator implements CollectionTranslatorInterface
{

    /**
     * The used locale hierarchy.
     *
     * @var string[]
     */
    private $localeHierarchy = [];

    /**
     * The used message selector.
     *
     * @var SelectorInterface
     */
    private $selector = null;

    /**
     * The used message filter.
     *
     * @var FilterInterface
     *
     */
    private $filter = null;

    /**
     * Map with composite collections named by the used locales.
     *
     * @var CompositeCollectionInterface[]
     */
    private $collections = [];

    /**
     * Constructor.
     *
     * @param string[] $localeHierarchy
     *            The used locale hierarchy.
     * @param SelectorInterface $selector
     *            The used message selector.
     * @param FilterInterface $filter
     *            The used message filter.
     * @param CollectionInterface[] $collections
     *            List of message collections to add.
     */
    public function __construct(array $localeHierarchy, SelectorInterface $selector, FilterInterface $filter, array $collections)
    {
        $this->localeHierarchy = $localeHierarchy;
        $this->selector = $selector;
        $this->filter = $filter;

        foreach ($collections as $collection) {
            $this->addCollection($collection);
        }
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
        $selector = $this->selector;

        // if no value passed the null selector will be used.
        if ($value === null) {
            $selector = new NullSelector();
            $value = 0;
        }

        $context = $context ?? new Map();

        $localeHierarchy = array_merge([
            $locale
        ], $this->localeHierarchy);

        foreach ($localeHierarchy as $locale) {
            if (! array_key_exists($locale, $this->collections)) {
                continue;
            }

            if (! $this->collections[$locale]->has($messageId)) {
                continue;
            }

            $messages = $this->collections[$locale]->get($messageId);
            $message = $selector->choose($locale, $messages, $value);

            return $this->filter->filter($this, $locale, $message, $context);
        }

        throw new \OutOfBoundsException(sprintf('Message "%s" is not contained in this translator.', $messageId));
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Translator\CollectionTranslatorInterface::addCollection($collection)
     */
    public function addCollection(CollectionInterface $collection): CollectionTranslatorInterface
    {
        $locale = $collection->getLocale();

        if (! array_key_exists($locale, $this->collections)) {
            $this->collections[$locale] = new CompositeCollection($locale);
        }

        $this->collections[$locale]->addCollection($collection);

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Translator\CollectionTranslatorInterface::getCollections()
     */
    public function getCollections(): array
    {
        return $this->collections;
    }
}
