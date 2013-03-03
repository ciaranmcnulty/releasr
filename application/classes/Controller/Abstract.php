<?php

/**
* Class containing a few util functions
*
* @todo Move SVN functionality into a subclass
* @package Releasr
*/
abstract class Releasr_Controller_Abstract
{
    /**
     * @var Releasr_Repo_UrlResolver Configuration of how the target repository is set up
     */
    protected $_urlResolver;

    /**
    * @var Releasr_Repo_Runner
    */
    protected $_svnRunner;

    /**
    * @param Releasr_Repo_UrlResolver $urlResolver The config of the repository
    * @param Releasr_Repo_Runner $svnRunner The object to use to execute SVN commands
    */
    public function __construct($urlResolver, $svnRunner)
    {
        $this->_urlResolver = $urlResolver;
        $this->_svnRunner = $svnRunner;
    }
}