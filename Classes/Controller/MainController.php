<?php
namespace RWB\Rwbfeupload\Controller;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;

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
 * MainController
 */
class MainController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * mainRepository
     *
     * @var \RWB\Rwbfeupload\Domain\Repository\MainRepository
     * @inject
     */
    protected $mainRepository = NULL;

    /**
     * @var \RWB\Rwbfeupload\Service\UploadFileService
     * @inject
     */
    protected $uploadFileService = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $mains = $this->mainRepository->findAll();
        $this->view->assign('mains', $mains);
    }
    
    /**
     * action show
     *
     * @param \RWB\Rwbfeupload\Domain\Model\Main $main
     * @return void
     */
    public function showAction(\RWB\Rwbfeupload\Domain\Model\Main $main)
    {
        $this->view->assign('main', $main);
    }
    
    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
    }


    /**
     * action create
     *
     * @param \RWB\Rwbfeupload\Domain\Model\Main $newMain
     * @return void
     */
    public function createAction(\RWB\Rwbfeupload\Domain\Model\Main $newMain)
    {
        $persistenceManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $this->mainRepository->add($newMain);
        $persistenceManager->persistAll();
        $uploadedFiles = $this->uploadFileService->getUploadedFiles('file');
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->addFiles($uploadedFiles, $newMain);
        $this->redirect('list');
    }
    
    /**
     * action edit
     *
     * @param \RWB\Rwbfeupload\Domain\Model\Main $main
     * @ignorevalidation $main
     * @return void
     */
    public function editAction(\RWB\Rwbfeupload\Domain\Model\Main $main)
    {
        $this->view->assign('main', $main);
    }
    
    /**
     * action update
     *
     * @param \RWB\Rwbfeupload\Domain\Model\Main $main
     * @return void
     */
    public function updateAction(\RWB\Rwbfeupload\Domain\Model\Main $main)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->mainRepository->update($main);
        $this->redirect('list');
    }
    
    /**
     * action delete
     *
     * @param \RWB\Rwbfeupload\Domain\Model\Main $main
     * @return void
     */
    public function deleteAction(\RWB\Rwbfeupload\Domain\Model\Main $main)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->mainRepository->remove($main);
        $this->redirect('list');
    }
    
    /**
     * action
     *
     * @return void
     */
    public function Action()
    {
        
    }



    /**
     * @param $uploadedFiles
     * @param $main
     */
    public function addFiles($uploadedFiles, $main)
    {


        $persistenceManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        # A property name is needed in case specified in the Fluid Widget
        # <mu:widget.upload property="foo"/>
        # Process uploaded files and move them into a Resource Storage (FAL)
        foreach ($uploadedFiles as $uploadedFile) {
            $isReference = strpos(get_class($uploadedFile), 'FileReference') === false ? false : true;
            //get file and move it if it was just uploaded
            if ($isReference === false) {
                //get Storage and target
                $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
                $storage = $storageRepository->findByUid($this->settings['storage']);


                $targetFolder = ResourceFactory::getInstance()->createFolderObject($storage, $this->settings['uploadFolder'], 'uploadFolder');
//                DebuggerUtility::var_dump($targetFolder,'MainController:171');

                $originalFilePath = $uploadedFile->getTmpName();
                $newFileName = $uploadedFile->getSanitizedFileName();
                if (file_exists($originalFilePath)) {
                    //move file to its last place and store it in $file
                    $file = $storage->addFile($originalFilePath, $targetFolder, $newFileName);
                }
            }
            if ($isReference === true) {
                //get file from the given refrence and store it in $file
                $file = $this->fileRepository->findFileReferenceByUid($uploadedFile->getUid())->getOriginalFile();
            }
            //make new reference
            $newFileReference = $this->objectManager->get('RWB\\Rwbfeupload\\Domain\\Model\\FileReference');
            $newFileReference->setFile($file);
            //add reference to main
            $main->addFileReference($newFileReference);
            $this->mainRepository->update($main);
            $persistenceManager->persistAll();
        }
        return $main;
    }

}