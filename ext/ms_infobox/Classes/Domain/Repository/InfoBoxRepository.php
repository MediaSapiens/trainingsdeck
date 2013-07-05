<?php
namespace ms\MsInfobox\Domain\Repository;

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
class InfoBoxRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

  public function unsetStoragePid() {
    $querySettings = $this->objectManager->create('Tx_Extbase_Persistence_Typo3QuerySettings');
    $querySettings->setRespectStoragePage(FALSE);
    $this->setDefaultQuerySettings($querySettings);
  }

  public function getItems($itemsUIDs,  $limit = 3) {
    $query = $this->createQuery();
    $constrains = array();
    $constrains[] = $query->in('uid', $itemsUIDs);
    $query
        ->matching(
          $query->logicalAnd($constrains)
        )
        #order('FIELD(id, '.implode(',',$pIdsSql).')')
        /*->setOrderings(array(
          'FIELD (uid, ' . implode(', ', $itemsUIDs) .')' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_FIELD
        ))*/
        #->setOrderings(array('uid' => implode(',', $itemsUIDs)))
        ->setLimit((integer)$limit);

    #$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
    #$GLOBALS['TYPO3_DB']->debugOutput = true;
    $items = $query->execute();
    #echo $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;

    $itemsOrdered = array();
    foreach ($itemsUIDs as $uid) {
      foreach ($items as $item) {
        $currentUid = $item->getUid();
        if($uid == $currentUid) {
          $itemsOrdered[] = $item;
          break;
        }
      }
    }

    return $itemsOrdered;
  }


}
?>