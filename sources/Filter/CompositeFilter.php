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
namespace Nia\Translating\Filter;

use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Translating\Translator\TranslatorInterface;

/**
 * Composite filter implementation.
 */
class CompositeFilter implements CompositeFilterInterface
{

    /**
     * List with added filters.
     *
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * Constructor.
     *
     * @param FilterInterface[] $filters
     *            List of filters to add.
     */
    public function __construct(array $filters = [])
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Filter\FilterInterface::filter($translator, $locale, $message, $context)
     */
    public function filter(TranslatorInterface $translator, string $locale, string $message, MapInterface $context): string
    {
        foreach ($this->filters as $filter) {
            $message = $filter->filter($translator, $locale, $message, $context);
        }

        return $message;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Filter\CompositeFilterInterface::addFilter($filter)
     */
    public function addFilter(FilterInterface $filter): CompositeFilterInterface
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Filter\CompositeFilterInterface::getFilters()
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}