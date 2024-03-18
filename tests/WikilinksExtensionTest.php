<?php

/** @noinspection HtmlUnknownTarget */

use Cassarco\LeagueCommonmarkWikilinks\WikilinksExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

function convert(string $markdown, array $options = []): string
{
    $environment = new Environment([
        'wikilinks' => $options,
    ]);

    $environment->addExtension(new CommonMarkCoreExtension());
    $environment->addExtension(new WikiLinksExtension());

    $converter = new MarkdownConverter($environment);

    try {
        return trim($converter->convert($markdown)->getContent());
    } catch (CommonMarkException $e) {
        return $e->getMessage();
    }
}

it('can convert a wikilink to an html link', function () {
    $markdown = '[[Hello World]]';
    $html = '<p><a href="hello-world" title="Hello World">Hello World</a></p>';

    expect(convert($markdown))->toEqual($html);
});

it('can handle empty wikilinks', function () {
    $markdown = '[[]]';
    $html = '<p>[[]]</p>';

    expect(convert($markdown))->toEqual($html);
});

it('can handle whitespace', function () {
    $markdown = '[[      Hello World]]';
    $html = '<p><a href="hello-world" title="      Hello World">      Hello World</a></p>';

    expect(convert($markdown))->toEqual($html);

    $markdown = '[[Hello World         ]]';
    $html = '<p><a href="hello-world" title="Hello World         ">Hello World         </a></p>';

    expect(convert($markdown))->toEqual($html);
});

it('can handle looks-like text', function () {
    $markdown = '[[Hello World|Welcome]]';
    $html = '<p><a href="hello-world" title="Welcome">Welcome</a></p>';

    expect(convert($markdown))->toEqual($html);
});

it('can handle hash links', function () {
    $markdown = '[[Hello World#Top]]';
    $html = '<p><a href="hello-world#top" title="Hello World &gt; Top">Hello World &gt; Top</a></p>';

    expect(convert($markdown))->toEqual($html);
});

it('can handle hash links with looks-like text', function () {
    $markdown = '[[Hello World#Top|Welcome]]';
    $html = '<p><a href="hello-world#top" title="Welcome">Welcome</a></p>';

    expect(convert($markdown))->toEqual($html);
});

it('has a prefix configuration option that prepends a prefix to the url', function () {
    $markdown = '[[Hello World#Top|Welcome]]';
    $html = '<p><a href="articles/hello-world#top" title="Welcome">Welcome</a></p>';

    expect(convert($markdown, [
        'prefix' => 'articles/',
    ]))->toEqual($html);
});

it('does nothing if more than one hash is found', function () {
    $markdown = '[[Hello World###Top|Welcome]]';
    $html = '<p>[[Hello World###Top|Welcome]]</p>';

    expect(convert($markdown))->toEqual($html);
});

it('does nothing if more than one vertical bar is found', function () {
    $markdown = '[[Hello World|||Welcome]]';
    $html = '<p>[[Hello World|||Welcome]]</p>';

    expect(convert($markdown))->toEqual($html);
});

it('does nothing if it finds more than two right angle bracket', function () {
    $markdown = '[[Hello World]]]]';
    $html = '<p>[[Hello World]]]]</p>';

    expect(convert($markdown))->toEqual($html);
});

it('does nothing if it finds more than two left angle brackets', function () {
    $markdown = '[[[[Hello World]]';
    $html = '<p>[[[[Hello World]]</p>';

    expect(convert($markdown))->toEqual($html);
});

it('does nothing if two surrounding square brackets are not found', function () {

    $markdown = '[Hello World]';
    $html = '<p>[Hello World]</p>';

    expect(convert($markdown))->toEqual($html);

    $markdown = 'Hello World';
    $html = '<p>Hello World</p>';

    expect(convert($markdown))->toEqual($html);

    $markdown = '[[Hello World';
    $html = '<p>[[Hello World</p>';

    expect(convert($markdown))->toEqual($html);

    $markdown = 'Hello World]]';
    $html = '<p>Hello World]]</p>';

    expect(convert($markdown))->toEqual($html);
});

it('does not affect other parsers that use brackets', function () {
    $markdown = '[Hello World](https://www.example.com)';
    $html = '<p><a href="https://www.example.com">Hello World</a></p>';

    expect(convert($markdown))->toEqual($html);

    $markdown = '![alt text](image.jpg)';
    $html = '<p><img src="image.jpg" alt="alt text" /></p>';

    expect(convert($markdown))->toEqual($html);
});
