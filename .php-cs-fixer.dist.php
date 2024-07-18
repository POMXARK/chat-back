<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in([
        __DIR__,
    ])
    ->path([
        'app',
        'config',
        'database',
        'routes',
        'tests',
        '.php-cs-fixer.dist.php',
    ])
;

return (new Config())
    ->setRules([
        '@PSR1'                  => true,
        '@PSR2'                  => true,
    ])
    ->setFinder($finder)
;
