<?php

namespace Cassar\LeagueCommonmarkWikilinks;

use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

class WikilinksInlineParser implements InlineParserInterface, ConfigurationAwareInterface
{
    protected ConfigurationInterface $config;

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex('\[\[([^\[^\]]+)\]\]');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        [$text] = $inlineContext->getSubMatches();

        $cursor = $inlineContext->getCursor();

        $line = $cursor->getLine();

        if ($this->doesNotPassValidation($text, $line)) {
            return false;
        }

        $cursor->advanceBy($inlineContext->getFullMatchLength());

        $inlineContext->getContainer()->appendChild(
            Link::new()->withConfig($this->config)->from($text)
        );

        return true;
    }

    private function doesNotPassValidation(string $text, string $line): bool
    {
        return !$text ||
            str_contains($text, '[') ||
            str_contains($text, ']') ||
            substr_count($line, '[') > 2 ||
            substr_count($line, ']') > 2 ||
            substr_count($line, '#') > 1 ||
            substr_count($line, '|') > 1;
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }
}
