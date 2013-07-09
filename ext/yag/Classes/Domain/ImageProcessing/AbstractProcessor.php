<?php
/***************************************************************
* Copyright notice
*
*   2010 Daniel Lienert <daniel@lienert.cc>, Michael Knoll <mimi@kaktusteam.de>
* All rights reserved
*
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* Abstract image processor
*
* @package Domain
* @subpackage ImageProcessing
* @author Daniel Lienert <daniel@lienert.cc>
*/

abstract class Tx_Yag_Domain_ImageProcessing_AbstractProcessor implements Tx_Yag_Domain_ImageProcessing_ProcessorInterface {

	
	/**
	 * Holds configuration for image processor
	 *
	 * @var Tx_Yag_Domain_Configuration_ImageProcessing_ImageProcessorConfiguration
	 */
    protected $processorConfiguration;

	
	/**
     * Holds an instance of hash file system for this gallery
     *
     * @var Tx_Yag_Domain_FileSystem_HashFileSystem
     */
    protected $hashFileSystem;
    
    
    
    /**
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected $configurationManager;
	
	
	
	/**
	 * @var Tx_Yag_Domain_Repository_ResolutionFileCacheRepository
	 */
	protected $resolutionFileCacheRepository;


	/**
	 * @var Tx_Yag_Domain_FileSystem_Div
	 */
	protected $fileSystemDiv;


	/**
	 * @param Tx_Yag_Domain_Configuration_ImageProcessing_ImageProcessorConfiguration $processorConfiguration
	 */
	public function _injectProcessorConfiguration(Tx_Yag_Domain_Configuration_ImageProcessing_ImageProcessorConfiguration $processorConfiguration) {
		$this->processorConfiguration = $processorConfiguration;
	}


	/**
	 * @param Tx_Yag_Domain_FileSystem_HashFileSystem $hashFileSystem
	 */
	public function _injectHashFileSystem(Tx_Yag_Domain_FileSystem_HashFileSystem $hashFileSystem) {
		$this->hashFileSystem = $hashFileSystem;
	}


	/**
	 * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
	 */
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) {
		$this->configurationManager = $configurationManager;
	}


	/**
	 * @param Tx_Yag_Domain_Repository_ResolutionFileCacheRepository $resolutionFileCacheRepository
	 */
	public function injectResolutionFileCacheRepository(Tx_Yag_Domain_Repository_ResolutionFileCacheRepository $resolutionFileCacheRepository) {
		$this->resolutionFileCacheRepository = $resolutionFileCacheRepository;
	}


	/**
	 * @param Tx_Yag_Domain_FileSystem_Div $fileSystemDiv
	 */
	public function injectResolutionFileSystemDiv(Tx_Yag_Domain_FileSystem_Div $fileSystemDiv) {
		$this->fileSystemDiv = $fileSystemDiv;
	}

	
	/**
	 * 
	 * Init the concrete image processor
	 */
	public function init() {}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/ImageProcessing/Tx_Yag_Domain_ImageProcessing_ProcessorInterface::generateResolution()
	 */
	public function generateResolution(Tx_Yag_Domain_Model_Item $origFile, Tx_Yag_Domain_Configuration_Image_ResolutionConfig $resolutionConfiguration) {
		
		$resolutionFile = new Tx_Yag_Domain_Model_ResolutionFileCache($origFile,'',0,0,$resolutionConfiguration->getParameterHash());
    	
    	$this->resolutionFileCacheRepository->add($resolutionFile);

		$this->processFile($resolutionConfiguration, $origFile, $resolutionFile);
		
		return $resolutionFile;
	}
	
	
	
	/**
	 * Process a file and set the resulting path in the resolution file object
	 * 
	 * @param Tx_Yag_Domain_Configuration_Image_ResolutionConfig $resolutionConfiguration
	 * @param Tx_Yag_Domain_Model_Item $origFile
	 * @param Tx_Yag_Domain_Model_ResolutionFileCache $resolutionFile
	 */
	abstract protected function processFile(Tx_Yag_Domain_Configuration_Image_ResolutionConfig $resolutionConfiguration, Tx_Yag_Domain_Model_Item $origFile, Tx_Yag_Domain_Model_ResolutionFileCache $resolutionFile);
	
	
	
	/**
	 * Build and return the target file path of the resolution file
	 * 
	 * @param string $extension
	 * @param string $imageName
	 * @return string $targetFilePath
	 */
	protected function generateAbsoluteResolutionPathAndFilename($extension = 'jpg', $imageName = '') {

		// We need an UID for the item file
    	$nextUid = $this->resolutionFileCacheRepository->getCurrentUid();

    	// Get a path in the hash filesystem
      	$resolutionFileName = $this->getMeaningfulTempFilePrefix($imageName) . substr(uniqid($nextUid.'_'),0,16);
    	$targetFilePath = $this->hashFileSystem->createAndGetAbsolutePathById($nextUid) . '/' . $resolutionFileName . '.' . $extension;
    	
    	return $targetFilePath;
	}



	/**
	 * @param $imageName
	 * @return string
	 */
	protected function getMeaningfulTempFilePrefix($imageName) {

		if($this->processorConfiguration->getMeaningfulTempFilePrefix() > 0 && $imageName != '') {

			$cleanFileName = $this->fileSystemDiv->cleanFileName($imageName);

			if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem']) {
				$t3libCsInstance = t3lib_div::makeInstance('t3lib_cs'); /** @var $t3libCsInstance t3lib_cs */
				$meaningfulPrefix = $t3libCsInstance->substr('utf-8', $cleanFileName, 0, $this->processorConfiguration->getMeaningfulTempFilePrefix());
			} else {
				$meaningfulPrefix = substr($cleanFileName, 0, $this->processorConfiguration->getMeaningfulTempFilePrefix());
			}

			$meaningfulPrefix .= '_';

			return $meaningfulPrefix;
		}

		return '';
	}
}
?>