# league-commonmark-wikilinks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cassarco/league-commonmark-wikilinks.svg?style=flat-square)](https://packagist.org/packages/cassarco/league-commonmark-wikilinks)
[![Tests](https://img.shields.io/github/actions/workflow/status/cassarco/league-commonmark-wikilinks/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cassarco/league-commonmark-wikilinks/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/cassarco/league-commonmark-wikilinks.svg?style=flat-square)](https://packagist.org/packages/cassarco/league-commonmark-wikilinks)

An extension for league-commonmark that lets you use Wikilinks in your markdown files. These will be converted to html as you have come to expect from other apps that support Wikilinks such as Wikipedia and Obsidian.

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

(new MarkdownConverter($environment))->convert($markdown);

// <p><a href="hello-world" title="Hello World">Hello World</a></p>
```

## Features

### Convert Wikilinks to HTML Links

Surround text in double braces and this extension will automatically convert the Wikilink into an html link with a suitable title and text. 

```php
$markdown = '[[Hello World]]';

// <p><a href="hello-world" title="Hello World">Hello World</a></p>
```

### Support for Looks-like Text

Looks-like text can be used to modify the title and text of the resulting `a` tag.

```php 
$markdown = '[[Hello World|Welcome]]';

// <p><a href="hello-world" title="Welcome">Welcome</a></p>
```

### Support for Hash Links

You can optionally include hash links by adding a `#` followed by the hash link text to the end of the Wikilink.

```php
$markdown = '[[Hello World#Top]]';

// <p><a href="hello-world#top" title="Hello World &gt; Top">Hello World &gt; Top</a></p>
```

### Support for Hash Links combined with Looks-like Text

Of course, you can use looks-like text and hash links in combination.

```php
$markdown = '[[Hello World#Top|Welcome]]';

// <p><a href="hello-world#top" title="Welcome">Welcome</a></p>
```

### Support for Link Prefixes

A `prefix` configuration option can be used to prefix a string to the beginning of the href url.

```php
$markdown = '[[Hello World#Top|Welcome]]';

(new MarkdownConverter($environment))->convert($markdown, [
    'prefix' => 'articles/',
]

// <p><a href="articles/hello-world#top" title="Welcome">Welcome</a></p>
```

### Handles Edge Cases Gracefully

I've tried to think of all the edge cases, but there will doubtless be more. Please submit a bug report if you encounter any issues and I will try to resolve them.

```php
    $markdown = '[[]]'; //<p>[[]]</p>
    $markdown = '[Hello World]'; // <p>[Hello World]</p>
    $markdown = '[[Hello World'; // <p>[[Hello World</p>
    $markdown = 'Hello World]]'; // <p>Hello World]]</p>
    $markdown = '[[Hello World]]]]'; // <p>[[Hello World]]]]</p>
    $markdown = '[[      Hello World]]'; // <p><a href="hello-world" title="      Hello World">      Hello World</a></p>
    $markdown = '[[Hello World|||Welcome]]'; // <p>[[Hello World|||Welcome]]</p>
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you find a bug  that impacts the security of this package please send an email to security@cassar.co instead of using the issue tracker.

## Credits

- [Carl Cassar](https://carlcassar.com)
- [Cassar & Co](https://github.com/cassarco)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
