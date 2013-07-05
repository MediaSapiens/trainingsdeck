<?php
namespace ms\MsInfobox\ViewHelpers;

/**
 * View helper for rendering TYPO3 link
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class LinkWrapperViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper  {

  /**
   * Initialize arguments
   *
   * @return void
   */
  public function initializeArguments() {
    parent::initializeArguments();
    $this->registerUniversalTagAttributes();
    $this->registerTagAttribute("link", "string", "TYPO3 link", FALSE);
    $this->registerTagAttribute("linkCss", "string", "link css class", FALSE);
    $this->registerTagAttribute("linkText", "string", "link text", FALSE);
    $this->registerTagAttribute("linkTitle", "string", "link title", FALSE);
    $this->registerTagAttribute("isLinkUrl", "string", "get only Link Url", FALSE);
  }

  /**
   * Render the record
   *
   * @param int TYPO3Link
   * @param array $arguments
   *
   * @return html The rendered Link
   */
  public function render($TYPO3Link, $arguments = array()) {


    /*$params = explode(' ', $TYPO3Link);

    echo $GLOBALS['TSFE']->cObj->getTypoLink_URL(1);

    print_r($params);


    $pageType = '';
    $additionalParams = $argumentsToBeExcludedFromQueryString = array();
    $absolute = true;
    $pageUid = 1;

    $uriBuilder = $this->controllerContext->getUriBuilder();
    $uri = $uriBuilder->reset()->setTargetPageUid($pageUid)->setTargetPageType($pageType)->setArguments($additionalParams)->setCreateAbsoluteUri($absolute)->setAddQueryString($addQueryString)->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)->build();

    print_r($TYPO3Link);
    print_r($uri);
    echo '<hr>';
    die();

    $this->tag->addAttribute('href', $uri);
    $this->tag->setContent($this->renderChildren());

    return;*/


    $cObj = $GLOBALS['TSFE']->cObj;
    $piVars = array();
    $params = explode(' ', $TYPO3Link);
    if(!empty($arguments['linkCss'])) {
      if(!empty($params[2]))
        unset($params[2]);

      if(empty($params[1]))
        $params[1] = '_blank';
        //$params[1] = '-';

      $params['ATagParams.'] = $arguments['linkCss'];
      $TYPO3Link = implode(' ', $params);
    }
    if($arguments['isLinkUrl'] == 'true') {
      $formattedLink = $cObj->getTypoLink_URL($TYPO3Link);
    }
    else
      $formattedLink = $cObj->getTypoLink($arguments['linkText'], $TYPO3Link, $piVars);

    #die($formattedLink);
    return $formattedLink;
  }
}
?>