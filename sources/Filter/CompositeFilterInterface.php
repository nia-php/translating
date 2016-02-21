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

/**
 * Interface for composite filter implementations.
 * Composite filters are used to cobine multiple filters.
 */
interface CompositeFilterInterface extends FilterInterface
{

    /**
     * Adds a filter.
     *
     * @param FilterInterface $filter
     *            The filter to add.
     * @return CompositeFilterInterface Reference to this instance.
     */
    public function addFilter(FilterInterface $filter): CompositeFilterInterface;

    /**
     * Returns a list of all assigned filters.
     *
     * @return FilterInterface[] List with all assigned filters.
     */
    public function getFilters(): array;
}