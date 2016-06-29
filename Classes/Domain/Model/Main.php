<?php
namespace RWB\Rwbfeupload\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Falk Röder <mail@falk-roeder.de>, Röder Webdesign Berlin
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Main
 */
class Main extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * file
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $file = null;
    
    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }



    /**
     * Returns the file
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the file
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $files
     * @return void
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Removes a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove
     * @return void
     */
    public function removeFileReference(\TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove)
    {
        $this->file->detach($fileToRemove);
    }

    /**
     * adds a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToAdd
     * @return void
     */
    public function addFileReference(\TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToAdd)
    {
        if (!is_object($this->file)) {
            $this->file = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        }
        $this->file->attach($fileToAdd);
    }

}