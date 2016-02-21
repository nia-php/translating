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
namespace Nia\Translating\Collection;

use InvalidArgumentException;
use Iterator;
use Nia\Collection\Map\StringMap\MapInterface;
use OutOfBoundsException;

/**
 * Composite collection implementation.
 */
class CompositeCollection implements CompositeCollectionInterface
{

    /**
     * The used locale.
     *
     * @var string
     */
    private $locale = null;

    /**
     * List with assigned collections.
     *
     * @var CollectionInterface[]
     */
    private $collections = [];

    /**
     * Constructor.
     *
     * @param string $locale
     *            The used locale.
     * @param CollectionInterface[] $collections
     *            List with collections to assign.
     */
    public function __construct(string $locale, array $collections = [])
    {
        $this->locale = $locale;

        foreach ($collections as $collection) {
            $this->addCollection($collection);
        }
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Collection\CollectionInterface::getLocale()
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Collection\CollectionInterface::has($messageId)
     */
    public function has(string $messageId): bool
    {
        foreach ($this->collections as $collection) {
            if ($collection->has($messageId)) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Collection\CollectionInterface::get($messageId)
     */
    public function get(string $messageId): MapInterface
    {
        foreach ($this->collections as $collection) {
            if ($collection->has($messageId)) {
                return $collection->get($messageId);
            }
        }

        throw new \OutOfBoundsException(sprintf('Message "%s" is not contained in this collection.', $messageId));
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Collection\CompositeCollectionInterface::addCollection($collection)
     */
    public function addCollection(CollectionInterface $collection): CompositeCollectionInterface
    {
        if ($this->locale !== $collection->getLocale()) {
            throw new \InvalidArgumentException(sprintf('Passed collection uses the locale "%s", but "%s" is required.', $collection->getLocale(), $this->locale));
        }

        $this->collections[] = $collection;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Collection\CompositeCollectionInterface::getCollections()
     */
    public function getCollections(): array
    {
        return $this->collections;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Iterator
    {
        return new \ArrayIterator($this->getCollections());
    }
}
