<?php

namespace Cassarco\LeagueCommonmarkWikilinks;

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

        if ($this->validationFails($text, $line)) {
            return false;
        }

        $cursor->advanceBy($inlineContext->getFullMatchLength());

        $inlineContext->getContainer()->appendChild(
            Link::new()->withConfig($this->config)->from($text)
        );

        return true;
    }

    private function validationFails(string $text, string $line): bool
    {
        return
            $this->thereIsNo($text) ||
            $this->contains(character: '[', in: $text) ||
            $this->contains(character: ']', in: $text) ||
            $this->containsMoreThanTwoConsecutive(character: '#', in: $text) ||
            $this->containsMoreThanTwoConsecutive(character: '|', in: $text) ||
            $this->containsMoreThanTwoConsecutive(character: '[', in: $line) ||
            $this->containsMoreThanTwoConsecutive(character: ']', in: $line);
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    private function thereIsNo(string $text): bool
    {
        return !$text;
    }

    private function contains(string $character, string $in): bool
    {
        return str_contains($in, $character);
    }

    private function containsMoreThanTwoConsecutive(string $character, string $in): int|false
    {
        $pattern = '/[' . preg_quote($character, '/') . ']{3,}/';

        return preg_match($pattern, $in);
    }
}
