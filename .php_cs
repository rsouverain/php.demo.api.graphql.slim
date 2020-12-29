<?php

// @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/config.rst

$finder = PhpCsFixer\Finder::create()
    //->exclude('somedir')
    //->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
    ->in(__DIR__.'/app')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR2' => false,
        '@PSR12' => false, // TODO @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/4943#ref-issue-725230599
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;