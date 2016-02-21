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

use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Translating\Formatter\FormatterInterface;
use Nia\Translating\Formatter\NullFormatter;
use Nia\Collection\Map\StringMap\Map;
use Nia\Translating\Translator\TranslatorInterface;

/**
 * Filter to format values in a translated message.
 *
 *
 * Syntax:
 * {{ value }}
 * {{ value | formatterName }}
 * {{ value | formatterName (argument1=value1, argument... ) }}
 */
class ValueFormatterFilter implements FilterInterface
{

    /**
     * List with assigned formatters.
     *
     * @var FormatterInterface[]
     */
    private $formatters = [];

    /**
     * Constructor.
     *
     * @param FormatterInterface[] $formatters
     *            Native map with formatter names and formatters.
     */
    public function __construct(array $formatters)
    {
        $this->addFormatter('', new NullFormatter());

        foreach ($formatters as $formatterName => $formatter) {
            $this->addFormatter($formatterName, $formatter);
        }
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Translating\Filter\FilterInterface::filter($translator, $locale, $message, $context)
     */
    public function filter(TranslatorInterface $translator, string $locale, string $message, MapInterface $context): string
    {
        $regex = '/
        \{\{
            \s*
            # {{ value }}
            (?P<name>[a-z0-9_.-]+)
            (
                \s*
                \|
                \s*
                (
                    # {{ value | monetary }}
                    (?P<formatterName>[a-z0-9_-]+)
                    \s*
                    (
                        \(\s*
                            # {{ value | monetary (locale=de_DE, currency=EUR) }}
                            (?P<argumentList>[ a-z0-9_.,=-]*)
                        \s*\)
                    )?
                \s*
                )?
            )?
            \s*
        \}\}
        /ix';

        $matches = [];
        preg_match_all($regex, $message, $matches, PREG_SET_ORDER);

        foreach ($matches as $rawMatch) {
            $match = new Map($rawMatch);

            // try to get the value from context, if the value does not exist, the name is the value.
            $name = $match->get('name');
            $value = $context->tryGet($name, $name);

            $formatterName = $match->tryGet('formatterName', '');

            if (! array_key_exists($formatterName, $this->formatters)) {
                throw new \InvalidArgumentException(sprintf('Requested formatter "%s" is not registred.', $formatterName));
            }

            $arguments = new Map($this->extractArguments($match->tryGet('argumentList', '')));
            $replacement = $this->formatters[$formatterName]->format($translator, $locale, $value, $arguments);

            $message = str_replace($match->get('0'), $replacement, $message);
        }

        return $message;
    }

    /**
     * Adds a formatter
     *
     * @param string $formatterName
     *            Name of the formatter.
     * @param FormatterInterface $formatter
     *            The formatter.
     */
    private function addFormatter(string $formatterName, FormatterInterface $formatter)
    {
        $this->formatters[$formatterName] = $formatter;
    }

    /**
     * Extracts the arguments for a raw argument list.
     *
     * @param string $argumentList
     *            The raw argument list.
     * @return string[] Native map with extracted arguments.
     */
    private function extractArguments(string $argumentList): array
    {
        $arguments = [];

        foreach (array_map('trim', explode(',', $argumentList)) as $rawArgument) {
            if ($rawArgument === '') {
                continue;
            }

            list ($argumentName, $argumentValue) = array_map('trim', explode('=', $rawArgument, 2) + [
                null,
                null
            ]);

            $arguments[$argumentName] = $argumentValue;
        }

        return $arguments;
    }
}