# nia - Translating Component

Component provides several interfaces and classes for translations and pluralizations.

## Installation

Require this package with Composer.

```bash
	composer require nia/translating
```

## Tests
To run the unit test use the following command:

    $ cd /path/to/nia/component/
    $ phpunit --bootstrap=vendor/autoload.php tests/


## Formatters
To use the formatters from the `nia/formatting` component you can use the `nia/bridge-translating-formatting` component or for a more specfic use case just implement the `Nia\Translating\Formatter\FormatterInterface` interface.

## How to use
The following sample shows you how to use the `Nia\Translating\Translator\CollectionTranslator` with a locale hierarchy where `de_DE` is in the highest hierarchy and `en_US` in the lowest.

```php
	$germanCollection = new Collection('de_DE', [
	    'car-count' => new Map([
	        '0' => 'Du hast {{ value }} Auto',
	        '1' => 'Du hast {{ value }} Autos'
	    ])
	]);

	$englishCollection = new Collection('en_US', [
	    'car-count' => new Map([
	        '0' => 'You have {{ value }} car',
	        '1' => 'You have {{ value }} cars'
	    ]),
	    'message-count' => new Map([
	        '0' => 'You have {{ value }} new message',
	        '1' => 'You have {{ value }} new messages'
	    ])
	]);

	$translator = new CollectionTranslator([
	    'de_DE',
	    'en_US'
	], new PluralRuleSelector(), new ValueFormatterFilter([]), [
	    $germanCollection,
	    $englishCollection
	]);

	// de_DE: Du hast 1 Auto
	echo $translator->translate('car-count', 1, new Map(['value' => '1']));
	echo "\n";

	// de_DE: Du hast 123 Autos
	echo $translator->translate('car-count', 123, new Map(['value' => '123']));
	echo "\n";


	// de_DE (using en_US as next hierarchy level): You have 1 new message
	echo $translator->translate('message-count', 1, new Map(['value' => '1']));
	echo "\n";

	// de_DE (using en_US as next hierarchy level): You have 123 new messages
	echo $translator->translate('message-count', 123, new Map(['value' => '123']));
	echo "\n";
```
