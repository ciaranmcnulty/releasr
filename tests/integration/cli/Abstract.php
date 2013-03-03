<?php

/**
* @package Releasr
*/
abstract class Releasr_Integration_Cli_Abstract extends PHPUnit_Framework_Testcase
{
    /**
     * @var string Path to the repo
     */
    protected $_repoPath;

    /**
     * @var string Path to the config being used
     */
    private $_configPath;

    /**
     * @var string Path used for a checkout
     */
    private $_workingPath;

    /**
     * @var string Path to the executable
     */
    protected $_releasrPath;

    public function setUp()
    {
        $this->_releasrPath = realpath(dirname(__FILE__)) . '/../../../releasr.php';
        $this->_repoPath = $this->_setUpRepository();
        $this->_setUpBasePaths();
        $this->_configPath = $this->_writeConfigFile();
    }

    /**
     * Starts a repository and returns the pat
     *
     * @return string Path to the repository
     */
    private function _setUpRepository()
    {
        $repoPath = tempnam(sys_get_temp_dir(), 'Releasr_Repo_');
        unlink($repoPath);
        shell_exec('svnadmin create ' . escapeshellarg($repoPath));
        $repoPath = realpath($repoPath);
        return $repoPath;
    }

    /**
     * Starts the repo with folders as specified in the config file
     */
    private function _setUpBasePaths()
    {
        shell_exec('svn mkdir --parents file://' . $this->_repoPath . '/myproject/trunk -m "Trunk create"');
        shell_exec('svn mkdir --parents file://' . $this->_repoPath . '/myproject/releases -m "Branches create"');
    }

    /**
     * Writes a new config file and sets the env to point to it
     */ 
    private function _writeConfigFile()
    {
        $configPath = tempnam(sys_get_temp_dir(), 'Releasr_Repo_');

        $config = file_get_contents(dirname(__FILE__) . '/example-releasr.conf');
        $config = str_replace('[REPO]', 'file://' . $this->_repoPath, $config);
        file_put_contents($configPath, $config);
        putenv("RELEASR_CONFIG=$configPath");
        return $configPath;
    }

    /**
     * Checks out trunk, adds a file, then commits the change with a specified message
     *
     * @param string $message The commit message to use
     */
    protected function _commitNewFileOnTrunk($message)
    {
        $this->_checkOutTrunk();
        touch($this->_workingPath . '/file.txt');
        shell_exec('svn add ' . escapeshellarg($this->_workingPath) . '/file.txt');
        shell_exec('svn commit -m ' . escapeshellarg($message) . ' ' . escapeshellarg($this->_workingPath));
    }

    /**
     * Checks trunk out to $_workingPath
     */
    private function _checkOutTrunk()
    {
        $this->_workingPath = tempnam(sys_get_temp_dir(), 'Releasr_Working_');
        unlink($this->_workingPath);
        shell_exec('svn co file://' . escapeshellarg($this->_repoPath) . '/myproject/trunk ' . escapeshellarg($this->_workingPath));
    }

    public function tearDown()
    {   
        // won't work on non-unix
        shell_exec('rm -rf ' . escapeshellarg($this->_repoPath));
        unlink($this->_configPath);

        if ($this->_workingPath) {
            shell_exec('rm -rf ' . escapeshellarg($this->_workingPath));
        }
    }
}