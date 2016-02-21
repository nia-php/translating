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

use ArrayIterator;
use Iterator;
use Nia\Collection\Map\StringMap\MapInterface;
use OutOfBoundsException;

/**
 * Default message collection.
 */
class Collection implements CollectionInterface
{

    /**
     * The used locale.
     *
     * @var string
     */
    private $locale = null;

    /**
     * Assoc array with message maps.
     *
     * @var MapInterface[]
     */
    private $messages = [];

    /**
     * Constructor.
     *
     * @param string $locale
     *            The used locale.
     * @param MapInterface[] $messages
     *            Assoc array with message maps.
     */
    public function __construct(string $locale, array $messages)
    {
        $this->locale = $locale;

        foreach ($messages as $messageId => $message) {
            $this->addMessage($messageId, $message);
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
        return array_key_exists($messageId, $this->messages);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Collection\CollectionInterface::get($messageId)
     */
    public function get(string $messageId): MapInterface
    {
        if (! $this->has($messageId)) {
            throw new \OutOfBoundsException(sprintf('Message "%s" is not contained in this collection.', $messageId));
        }

        return $this->messages[$messageId];
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->messages);
    }

    /**
     * Adds a message.
     *
     * @param string $messageId
     *            The message id.
     * @param MapInterface $message
     *            The message.
     */
    private function addMessage(string $messageId, MapInterface $message)
    {
        $this->messages[$messageId] = $message;
    }
}
