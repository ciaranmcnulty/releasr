<?php

error_reporting(defined('E_DEPRECATED') ? (E_ALL | E_STRICT )^ E_DEPRECATED : E_ALL | E_STRICT);

if (!shell_exec('command -v svn')) { 
    echo 'Error: requires svn', PHP_EOL; 
    exit; 
}
if (!shell_exec('command -v svnadmin')) { 
    echo 'Error: requires svnadmin', PHP_EOL; 
    exit; 
}

require_once dirname(__FILE__) . '/unit/bootstrap.php';
require_once dirname(__FILE__) . '/integration/bootstrap.php';