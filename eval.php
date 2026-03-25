<?php
require_once 'wp-load.php';

$blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
$acf_blocks = array_filter(array_keys($blocks), function($b) { return strpos($b, 'acf/') === 0; });

echo implode("\n", $acf_blocks);
