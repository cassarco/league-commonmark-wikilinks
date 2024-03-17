# league-commonmark-wikilinks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cassarco/league-commonmark-wikilinks.svg?style=flat-square)](https://packagist.org/packages/cassarco/league-commonmark-wikilinks)
[![Tests](https://img.shields.io/github/actions/workflow/status/cassarco/league-commonmark-wikilinks/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cassarco/league-commonmark-wikilinks/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/cassarco/league-commonmark-wikilinks.svg?style=flat-square)](https://packagist.org/packages/cassarco/league-commonmark-wikilinks)

An extension that enables wikilinks parsing in league/commonmark.

## Installation

You can install the package via composer:

```bash
composer require cassarco/league-commonmark-wikilinks
```

## Usage

```php
use League\CommonMark\MarkdownConverter;
use Cassarco\LeagueCommonmarkWikilinks\WikilinksExtension;

$environment = new Environment();

$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new WikiLinksExtension());

$markdown = "[[Hello World]]";

$this->content = (new MarkdownConverter($environment))->convert($markdown);

// <p><a href="hello-world" title="Hello World">Hello World</a></p>
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Credits

- [Cassar & Co](https://github.com/cassarco)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
