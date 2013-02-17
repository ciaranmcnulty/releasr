<?php

/**
* Includes all classes
*
* @todo replace with autoloader
* @package Releasr
*/

require_once 'classes/Repo/Release.php';
require_once 'classes/Repo/Change.php';
require_once 'classes/Repo/Config.php';
require_once 'classes/Release/Abstract.php';
require_once 'classes/Release/Lister.php';
require_once 'classes/Release/Reviewer.php';
require_once 'classes/CliCommand/Interface.php';
require_once 'classes/CliCommand/Abstract.php';
require_once 'classes/CliCommand/Main.php';
require_once 'classes/CliCommand/List.php';
require_once 'classes/CliCommand/Review.php';
require_once 'classes/Exception/CliArgs.php';
require_once 'classes/Exception/Config.php';
require_once 'classes/Exception/Repo.php';