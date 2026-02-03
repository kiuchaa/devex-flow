<?php
/**
 * Plugin Update Checker configuration
 */

require_once get_template_directory() . '/libs/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/kiuchaa/devex-flow',
    get_template_directory() . '/style.scss',
    get_template() // This automatically gets the current folder name
);

// Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');