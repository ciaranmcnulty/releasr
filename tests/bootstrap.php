<?php

error_reporting(defined('E_DEPRECATED') ? (E_ALL | E_STRICT )^ E_DEPRECATED : E_ALL | E_STRICT);

require_once dirname(__FILE__) . '/unit/bootstrap.php';
require_once dirname(__FILE__) . '/integration/bootstrap.php';