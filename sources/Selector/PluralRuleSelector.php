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
namespace Nia\Translating\Selector;

use Nia\Collection\Map\StringMap\MapInterface;
use InvalidArgumentException;

/**
 * Message selector using plural rules.
 */
class PluralRuleSelector implements SelectorInterface
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Nia\Translating\Selector\SelectorInterface::choose($locale, $messages, $value)
     */
    public function choose(string $locale, MapInterface $messages, int $value): string
    {
        $index = (string) $this->getPluralIndex($locale, $value);

        return $messages->get($index);
    }

    /**
     * Returns the plural index by using locale and value.
     *
     * @param string $locale
     *            The used locale.
     * @param int $value
     *            The value.
     * @throws InvalidArgumentException If no plural rule exist for the language in the passed locale.
     * @return int The plural index.
     */
    private function getPluralIndex(string $locale, int $value): int
    {
        if ($locale === 'pt_BR') {
            $locale = 'xbr';
        }

        list ($language, $region) = explode('_', $locale, 2) + [
            null,
            null
        ];

        // The plural rules are derived from code of the Zend Framework (2010-09-25),
        // which is subject to the new BSD license (http://framework.zend.com/license/new-bsd).
        // Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
        switch ($language) {
            case 'az':
            case 'bo':
            case 'dz':
            case 'id':
            case 'ja':
            case 'jv':
            case 'ka':
            case 'km':
            case 'kn':
            case 'ko':
            case 'ms':
            case 'th':
            case 'tr':
            case 'vi':
            case 'zh':
                return 0;
            case 'af':
            case 'bn':
            case 'bg':
            case 'ca':
            case 'da':
            case 'de':
            case 'el':
            case 'en':
            case 'eo':
            case 'es':
            case 'et':
            case 'eu':
            case 'fa':
            case 'fi':
            case 'fo':
            case 'fur':
            case 'fy':
            case 'gl':
            case 'gu':
            case 'ha':
            case 'he':
            case 'hu':
            case 'is':
            case 'it':
            case 'ku':
            case 'lb':
            case 'ml':
            case 'mn':
            case 'mr':
            case 'nah':
            case 'nb':
            case 'ne':
            case 'nl':
            case 'nn':
            case 'no':
            case 'om':
            case 'or':
            case 'pa':
            case 'pap':
            case 'ps':
            case 'pt':
            case 'so':
            case 'sq':
            case 'sv':
            case 'sw':
            case 'ta':
            case 'te':
            case 'tk':
            case 'ur':
            case 'zu':
                return ($value == 1) ? 0 : 1;
            case 'am':
            case 'bh':
            case 'fil':
            case 'fr':
            case 'gun':
            case 'hi':
            case 'hy':
            case 'ln':
            case 'mg':
            case 'nso':
            case 'xbr':
            case 'ti':
            case 'wa':
                return (($value == 0) || ($value == 1)) ? 0 : 1;
            case 'be':
            case 'bs':
            case 'hr':
            case 'ru':
            case 'sr':
            case 'uk':
                return (($value % 10 == 1) && ($value % 100 != 11)) ? 0 : ((($value % 10 >= 2) && ($value % 10 <= 4) && (($value % 100 < 10) || ($value % 100 >= 20))) ? 1 : 2);
            case 'cs':
            case 'sk':
                return ($value == 1) ? 0 : ((($value >= 2) && ($value <= 4)) ? 1 : 2);
            case 'ga':
                return ($value == 1) ? 0 : (($value == 2) ? 1 : 2);
            case 'lt':
                return (($value % 10 == 1) && ($value % 100 != 11)) ? 0 : ((($value % 10 >= 2) && (($value % 100 < 10) || ($value % 100 >= 20))) ? 1 : 2);
            case 'sl':
                return ($value % 100 == 1) ? 0 : (($value % 100 == 2) ? 1 : ((($value % 100 == 3) || ($value % 100 == 4)) ? 2 : 3));
            case 'mk':
                return ($value % 10 == 1) ? 0 : 1;
            case 'mt':
                return ($value == 1) ? 0 : ((($value == 0) || (($value % 100 > 1) && ($value % 100 < 11))) ? 1 : ((($value % 100 > 10) && ($value % 100 < 20)) ? 2 : 3));
            case 'lv':
                return ($value == 0) ? 0 : ((($value % 10 == 1) && ($value % 100 != 11)) ? 1 : 2);
            case 'pl':
                return ($value == 1) ? 0 : ((($value % 10 >= 2) && ($value % 10 <= 4) && (($value % 100 < 12) || ($value % 100 > 14))) ? 1 : 2);
            case 'cy':
                return ($value == 1) ? 0 : (($value == 2) ? 1 : ((($value == 8) || ($value == 11)) ? 2 : 3));
            case 'ro':
                return ($value == 1) ? 0 : ((($value == 0) || (($value % 100 > 0) && ($value % 100 < 20))) ? 1 : 2);
            case 'ar':
                return ($value == 0) ? 0 : (($value == 1) ? 1 : (($value == 2) ? 2 : ((($value % 100 >= 3) && ($value % 100 <= 10)) ? 3 : ((($value % 100 >= 11) && ($value % 100 <= 99)) ? 4 : 5))));
        }

        throw new InvalidArgumentException(sprintf('No plural rule defined for "%s".', $locale));
    }
}
