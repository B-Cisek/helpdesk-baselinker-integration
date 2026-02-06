<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->exclude([
        'vendor',
        'var',
        'config',
        '.tools',
        '.docker'
    ])
    ->notPath([
        'public/index.php',
        'tests/bootstrap.php',
        'src/Kernel.php'
    ])
    ->in(__DIR__)
    ->name('*.php')
    ->ignoreDotFiles(true);

return new PhpCsFixer\Config()
    ->setFinder($finder)
    ->setRules([
        'declare_strict_types' => true,
        '@PER-CS' => true,
        '@PHP8x0Migration' => true,
        '@PHP8x1Migration' => true,
        '@PHP8x2Migration' => true,
        '@PHP8x3Migration' => true,
        '@PHP8x4Migration' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ]
    ]);
