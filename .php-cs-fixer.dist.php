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
        'cast_spaces' => [
            'space' => 'none',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'declare_strict_types' => true,
        'global_namespace_import' => [
            'import_classes' => false,
            'import_constants' => false,
            'import_functions' => null,
        ],
        'linebreak_after_opening_tag' => true,
        'native_function_invocation' => false,
        'no_superfluous_elseif' => false,
        'no_superfluous_phpdoc_tags' => false,
        'no_useless_else' => false,
        'ordered_imports' => true,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_test_annotation' => false,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'phpdoc_separation' => false,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_first',
            'sort_algorithm' => 'alpha',
        ],
        'yoda_style' => false,
    ])
    ->setFinder($finder);
