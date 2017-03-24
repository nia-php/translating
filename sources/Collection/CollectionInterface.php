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

use IteratorAggregate;
use Nia\Collection\Map\StringMap\MapInterface;
use OutOfBoundsException;

/**
 * Interface for message collection implementations.
 * Message collections are used to store multiple messages with message ids and plural forms.
 */
interface CollectionInterface extends IteratorAggregate
{

    /**
     * Returns the used locale of this collection.
     *
     * @return string The used locale of this collection.
     */
    public function getLocale(): string;

    /**
     * Checks whether a message exists by it's message id.
     *
     * @param string $messageId
     *            The message id of the message to check for.
     * @return bool Returns 'true' if the requested message exists, otherwise 'false' will be returned.
     */
    public function has(string $messageId): bool;

    /**
     * Returns the plural forms of the requested message.
     *
     * @param string $messageId
     *            The message id.
     * @throws OutOfBoundsException If the requested message does not exist.
     * @return MapInterface Map with plural forms of the requested message.
     */
    public function get(string $messageId): MapInterface;
}
