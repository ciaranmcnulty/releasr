<?php

/**
* Includes all classes
*
* @todo replace with autoloader
* @package Releasr
*/

require_once 'Repo/Release.php';
require_once 'Repo/Change.php';
require_once 'Repo/Config.php';
require_once 'Release/Abstract.php';
require_once 'Release/Lister.php';
require_once 'Release/Reviewer.php';
require_once 'Release/Preparer.php';
require_once 'CliCommand/Interface.php';
require_once 'CliCommand/Abstract.php';
require_once 'CliCommand/Main.php';
require_once 'CliCommand/List.php';
require_once 'CliCommand/Review.php';
require_once 'CliCommand/Prepare.php';
require_once 'Exception/CliArgs.php';
require_once 'Exception/Config.php';
require_once 'Exception/Repo.php';