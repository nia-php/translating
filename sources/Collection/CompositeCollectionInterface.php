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
namespace Nia\Translating\Collection;

use InvalidArgumentException;

/**
 * Interface for composite collection implementations.
 * Composite collections are used to combine multiple collections and use them as one.
 */
interface CompositeCollectionInterface extends CollectionInterface
{

    /**
     * Adds a collection.
     *
     * @param CollectionInterface $collection
     *            The collection to add.
     * @throws InvalidArgumentException If the locale of the adding collection is not the same of the composite collection.
     * @return CompositeCollectionInterface Reference to this instance.
     */
    public function addCollection(CollectionInterface $collection): CompositeCollectionInterface;

    /**
     * Returns a list of all assigned collections.
     *
     * @return CollectionInterface[] List with assigned collections.
     */
    public function getCollections(): array;
}
