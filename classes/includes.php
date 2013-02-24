<?php

/**
* Includes all classes
*
* @todo replace with autoloader
* @package Releasr
*/

require_once 'Repo/Release.php';
require_once 'Repo/Change.php';
require_once 'Repo/UrlResolver.php';
require_once 'Release/Abstract.php';
require_once 'Release/Lister.php';
require_once 'Release/Reviewer.php';
require_once 'Release/Preparer.php';
require_once 'CliCommand/Interface.php';
require_once 'CliCommand/DocumentedInterface.php';
require_once 'CliCommand/Meta/Abstract.php';
require_once 'CliCommand/Meta/Main.php';
require_once 'CliCommand/Meta/Help.php';
require_once 'CliCommand/Project/Abstract.php';
require_once 'CliCommand/Project/List.php';
require_once 'CliCommand/Project/Review.php';
require_once 'CliCommand/Project/Prepare.php';
require_once 'Exception/CliArgs.php';
require_once 'Exception/Config.php';
require_once 'Exception/Repo.php';