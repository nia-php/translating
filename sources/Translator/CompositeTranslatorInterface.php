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

/**
 * Interface for composite translator implementations.
 * Composite translators are used to combine multiple translators and use them as a single translator.
 */
interface CompositeTranslatorInterface extends TranslatorInterface
{

    /**
     * Adds a translator.
     *
     * @param TranslatorInterface $translator
     *            The translator to add.
     * @return CompositeTranslatorInterface Reference to this instance.
     */
    public function addTranslator(TranslatorInterface $translator): CompositeTranslatorInterface;

    /**
     * Returns a list of all assigned translators.
     *
     * @return TranslatorInterface[] List of all assigned translators.
     */
    public function getTranslators(): array;
}
