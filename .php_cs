<?php

use Symfony\CS\Config\Config;
use Symfony\CS\Finder\DefaultFinder;

$test = (boolean)getenv('TEST');

$fixers = [
    '-empty_return',
    '-include',
    '-phpdoc_separation',
    '-return',
    '-spaces_cast',
    'align_double_arrow',
    'concat_with_spaces',
    'ordered_use',
    'short_array_syntax',
];

$finder = DefaultFinder::create();

if (!$test) {
    $finder
        ->exclude(['node_modules', 'tests', 'vendor'])
        ->in(__DIR__);

    $fixers[] = 'align_equals';
} else {
    $finder->in(__DIR__ . '/tests');

    $fixers[] = '-unalign_equals';
}

return Config::create()
    ->fixers($fixers)
    ->finder($finder);
