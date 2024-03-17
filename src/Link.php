<?php

namespace Cassar\LeagueCommonmarkWikilinks;

use League\Config\ConfigurationInterface;

class Link
{
    protected string $title = '';
    protected string $url = '';
    protected array $split;
    protected ConfigurationInterface $config;
    private string $text;

    public static function new(): static
    {
        return new static;
    }

    public function from(string $text): \League\CommonMark\Extension\CommonMark\Node\Inline\Link {
        $this->text = $text;

        return $this
            ->split()
            ->identify()
            ->addPrefix()
            ->make();
    }

    public function withConfig(ConfigurationInterface $configuration): static
    {
        $this->config = $configuration;

        return $this;
    }

    public function make(): \League\CommonMark\Extension\CommonMark\Node\Inline\Link
    {
        return new \League\CommonMark\Extension\CommonMark\Node\Inline\Link(
            $this->url,
            $this->title,
            $this->title,
        );
    }

    protected function split(): static
    {
        $this->split = preg_split("/[#|]/", $this->text);

        return $this;
    }

    protected function identify(): static
    {
        match (true) {
            $this->isLink() => $this->processLink(),
            $this->isAliasedLink() => $this->processAliasedLink(),
            $this->isHashLink() => $this->processHashLink(),
            $this->isAliasedHashLink() => $this->processAliasedHashLink(),
        };

        return $this;
    }

    protected function slugFrom(string $text): string
    {
        $separator = '-';

        // Convert all dashes/underscores into separator
        $text = preg_replace('!['.preg_quote($separator).']+!u', $separator, $text);

        // Remove all characters that are not the separator, letters, numbers, or whitespace
        $text = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($text, 'UTF-8'));

        // Replace all separator characters and whitespace by a single separator
        $text = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $text);

        return trim($text, $separator);
    }

    protected function isLink(): bool
    {
        return count($this->split) == 1;
    }

    protected function isAliasedLink(): bool
    {
        return count($this->split) == 2 && str_contains($this->text, '|');
    }

    protected function isHashLink(): bool
    {
        return count($this->split) == 2 && str_contains($this->text, '#');
    }

    private function isAliasedHashLink(): bool
    {
        return count($this->split) == 3 && str_contains($this->text, '#');
    }

    protected function processLink(): void
    {
        $this->url = $this->slugFrom($this->split[0]);
        $this->title = $this->split[0];
    }

    protected function processAliasedLink(): void
    {
        $this->url = $this->slugFrom($this->split[0]);
        $this->title = $this->split[1];
    }

    protected function processHashLink(): void
    {
        $this->url = $this->slugFrom($this->split[0]).'#'.$this->slugFrom($this->split[1]);
        $this->title = $this->split[0].' > '.$this->split[1];
    }

    protected function processAliasedHashLink(): void
    {
        $this->url = $this->slugFrom($this->split[0]).'#'.$this->slugFrom($this->split[1]);
        $this->title = $this->split[2];
    }

    protected function addPrefix(): static
    {
        if($this->config->exists('wikilinks.prefix')) {
            $this->url = $this->config->get('wikilinks.prefix').$this->url;
        }

        return $this;
    }
}
