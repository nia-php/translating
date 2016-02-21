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

use Nia\Translating\Collection\CollectionInterface;

/**
 * Interface for collection translator implementations.
 * A collection translator use message collections to translate a message id.
 */
interface CollectionTranslatorInterface extends TranslatorInterface
{

    /**
     * Adds a collection.
     *
     * @param CollectionInterface $collection
     *            The collection to add.
     * @return CollectionTranslatorInterface Reference to this instance.
     */
    public function addCollection(CollectionInterface $collection): CollectionTranslatorInterface;

    /**
     * Returns a map with all assigned collections named by locale.
     *
     * @return CollectionInterface[] Map with all assigned collections named by locale.
     */
    public function getCollections(): array;
}
