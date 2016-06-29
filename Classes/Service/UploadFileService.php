<?php
namespace RWB\Rwbfeupload\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Fab\Media\FileUpload\UploadManager;
use Fab\MediaUpload\UploadedFile;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Uploaded files service.
 */
class UploadFileService {

	/**
	 * Return the list of uploaded files.
	 *
	 * @param string $property
	 * @return string
	 */
	public function getUploadedFileList($property = '') {
		$parameters = GeneralUtility::_GPmerged('tx_rwbfeupload_rwbfeupload');
		return empty($parameters['uploadedFiles'][$property]) ? '' : $parameters['uploadedFiles'][$property];
	}

	/**
	 * Return an array of uploaded files, done in a previous step.
	 *
	 * @param string $property
	 * @throws \Exception
	 * @return UploadedFile[]
	 */
	public function getUploadedFiles($property = '') {
 
		$files = array();

		$uploadedFiles = GeneralUtility::trimExplode(',', $this->getUploadedFileList($property), TRUE);

		// Convert uploaded files into array
		foreach ($uploadedFiles as $uploadedFileName) {

			$tmp_name = UploadManager::UPLOAD_FOLDER . '/' . $uploadedFileName;

			if (!file_exists($tmp_name)) {
				$message = sprintf(
					'I could not find file "%s". Something went wrong during the upload? Or is it some cache effect?',
					$tmp_name
				);

				throw new \Exception($message, 1389550006);
			}
			$fileSize = round(filesize($tmp_name) / 1000);

			/** @var \Fab\MediaUpload\UploadedFile $uploadedFile */
			$uploadedFile = GeneralUtility::makeInstance('Fab\MediaUpload\UploadedFile');
			$uploadedFile->setTmpName($tmp_name)
				->setFileName($uploadedFileName)
				->setSize($fileSize);

			$files[] = $uploadedFile;
		}

		return $files;
	}

	/**
	 * Return the first uploaded files, done in a previous step.
	 *
	 * @param string $property
	 * @return array
	 */
	public function getUploadedFile($property = '') {
		$uploadedFile = array();

		$uploadedFiles = $this->getUploadedFiles($property);
		if (!empty($uploadedFiles)) {
			$uploadedFile = current($uploadedFiles);
		}

		return $uploadedFile;
	}

	/**
	 * Count uploaded files.
	 *
	 * @param string $property
	 * @return array
	 */
	public function countUploadedFiles($property = '') {
		return count(GeneralUtility::trimExplode(',', $this->getUploadedFileList($property), TRUE));
	}

}
