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

/**
 * Composite translator implementation.
 */
class CompositeTranslator implements CompositeTranslatorInterface
{

    /**
     * List with assigned translators.
     *
     * @var TranslatorInterface[]
     */
    private $translators = [];

    /**
     * Constructor.
     *
     * @param TranslatorInterface[] $translators
     *            List of translators to assign.
     */
    public function __construct(array $translators = [])
    {
        foreach ($translators as $translator) {
            $this->addTranslator($translator);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Translator\CompositeTranslatorInterface::addTranslator()
     */
    public function addTranslator(TranslatorInterface $translator): CompositeTranslatorInterface
    {
        $this->translators[] = $translator;

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Translator\CompositeTranslatorInterface::getTranslators()
     */
    public function getTranslators(): array
    {
        return $this->translators;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Translator\TranslatorInterface::setLocaleHierarchy()
     */
    public function setLocaleHierarchy(array $localeHierarchy): TranslatorInterface
    {
        foreach ($this->translators as $translator) {
            $translator->setLocaleHierarchy($localeHierarchy);
        }

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Translator\TranslatorInterface::getLocaleHierarchy()
     */
    public function getLocaleHierarchy(): array
    {
        $result = [];

        foreach ($this->translators as $translator) {
            $result = array_merge($result, $translator->getLocaleHierarchy());
        }

        return array_unique($result);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Translator\TranslatorInterface::translate()
     */
    public function translate(string $messageId, int $value = null, MapInterface $context = null, string $locale = null): string
    {
        foreach ($this->translators as $translator) {
            try {
                return $translator->translate($messageId, $value, $context, $locale);
            } catch (\OutOfBoundsException $e) {}
        }

        throw new \OutOfBoundsException(sprintf('Message "%s" is not contained in this translator.', $messageId));
    }
}
