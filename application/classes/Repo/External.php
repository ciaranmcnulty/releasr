<?php

/**
 * Class representing an external property 
 *
 * @package Releasr
 */
class Releasr_Repo_External
{
    /**
     * @var string The path of the folder on which the external is set
     */
    public $path;

    /**
     * @var string A full externals property
     */
    public $property;

    /**
     * Works out whether the external has any unversioned properies
     *
     * @return boolean
     */
    public function hasUnversionedExternals()
    {
        $lines = split("\n", $this->property);
        foreach ($lines as $line) {
            if (trim($line) && !preg_match('/-r\s*[0-9]+/', $line)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}