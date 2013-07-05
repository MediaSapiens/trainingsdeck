<?php
namespace ms\MsInfobox\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Igor Novoseltsev <i.v.novoseltsev@gmail.com>, mediasapiens
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
 *
 *
 * @package ms_infobox
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class InfoBoxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

  /**
   * infoBoxRepository
   *
   * @var \ms\MsInfobox\Domain\Repository\InfoBoxRepository
   * @inject
   */
  protected $infoBoxRepository;

  /**
   * configuration array
   * @var array
   */
  protected $_FeConfigManager;


  /**
   * Init the actions
   */
  public function initializeAction() {
    parent::initializeAction();

    if(empty($this->_FeConfigManager['persistence']['storagePid']))
      $this->infoBoxRepository->unsetStoragePid();

    $this->_FeConfigManager = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

  }

  /**
   * action list
   *
   * @return void
   */
  public function listAction() {
    $itemsUIDs = $this->_FeConfigManager['settings']['itemsUIDs'];
    if(!empty($itemsUIDs))
      $itemsUIDs = explode(',', $itemsUIDs);
    $infoBoxes = $this->infoBoxRepository->getItems($itemsUIDs);
    $this->view->assign('infoBoxes', $infoBoxes);
  }

}
?>