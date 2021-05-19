<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()->in([
    __DIR__ . '/src',
    __DIR__ . '/test',
]);

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PSR12' => true,
        'blank_line_after_opening_tag' => false,
        'blank_line_before_statement' => false,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'declare_strict_types' => true,
        'global_namespace_import' => true,
        'linebreak_after_opening_tag' => true,
        'native_function_invocation' => false,
        'no_superfluous_elseif' => false,
        'no_superfluous_phpdoc_tags' => false,
        'no_useless_else' => false,
        'not_operator_with_successor_space' => true,
        'ordered_imports' => true,
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_first',
            'sort_algorithm' => 'alpha',
        ],
        'yoda_style' => false,
    ])
    ->setFinder($finder);
