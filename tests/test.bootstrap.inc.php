<?php

/**
 * PHPUnit bootstrap file.
 *
 * Set include path and initialize autoloader.
 *
 * @package    PHPDraft\Core
 * @author     Sean Molenaar <sean@seanmolenaar.eu>
 * @license    https://github.com/SMillerDev/phpdraft/blob/master/LICENSE GPLv3 License
 */

$base = __DIR__ . '/..';

set_include_path(
    $base . '/src:' .
    $base . '/tests:' .
    $base . '/tests/statics:' .
    get_include_path()
);

// Load and setup class file autloader
require_once $base . '/vendor/autoload.php';

if (defined('TEST_STATICS') === FALSE)
{
    define('TEST_STATICS', __DIR__ . '/statics');
}

if (defined('VERSION') === FALSE)
{
    define('VERSION', '0');
}

?>
