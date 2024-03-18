<?php

namespace Cassarco\LeagueCommonmarkWikilinks;

use Nette\Schema\Expect;
use League\Config\ConfigurationBuilderInterface;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ConfigurableExtensionInterface;

class WikilinksExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('wikilinks', Expect::structure([
            'prefix' => Expect::string()->default(''),
        ]));
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addInlineParser(new WikilinksInlineParser(), 50);
    }
}
